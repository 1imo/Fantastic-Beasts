<?php

require_once 'beast.php';

function normalize_text(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
    $text = preg_replace('/\s+/u', ' ', $text);
    return trim($text);
}

function singularize_basic(string $token): string {
    if (preg_match('/(ches|shes|xes|ses)$/u', $token)) {
        return preg_replace('/es$/u', '', $token);
    }
    if (preg_match('/ies$/u', $token)) {
        return preg_replace('/ies$/u', 'y', $token);
    }
    if (preg_match('/s$/u', $token) && !preg_match('/ss$/u', $token)) {
        return preg_replace('/s$/u', '', $token);
    }
    return $token;
}

function tokenize(string $text): array {
    $normalized = normalize_text($text);
    if ($normalized === '') {
        return [];
    }
    $tokens = preg_split('/\s+/u', $normalized);
    $stopwords = [
        'the','a','an','and','or','but','if','then','else','for','to','of','in','on','with','at','by','from','as','is','are','was','were','it','its','be','this','that','these','those','their','his','her','they','them','we','you','your','our','i','my','me'
    ];
    $stop = array_flip($stopwords);
    $filtered = [];
    foreach ($tokens as $t) {
        if ($t === '' || isset($stop[$t])) {
            continue;
        }
        $base = singularize_basic($t);
        $filtered[] = $base;
    }
    return $filtered;
}

function field_weighted_tokens(FantasticBeast $beast): array {
    $nameTokens = tokenize($beast->getName());
    $descTokens = tokenize($beast->getDescription());

    // Name gets higher weight than description
    $tokens = [];
    foreach ($nameTokens as $t) {
        $tokens[] = $t; $tokens[] = $t; $tokens[] = $t; $tokens[] = $t; $tokens[] = $t; $tokens[] = $t; // 6x name
    }
    foreach ($descTokens as $t) {
        $tokens[] = $t; // 1x description
    }

    return $tokens;
}

function build_corpus_documents(array $beasts): array {
    $documents = [];
    foreach ($beasts as $beast) {
        if (!($beast instanceof FantasticBeast)) {
            continue;
        }
        $documents[] = [
            'beast' => $beast,
            'tokens' => field_weighted_tokens($beast)
        ];
    }
    return $documents;
}

function compute_idf(array $documents): array {
    $numDocuments = max(1, count($documents));
    $documentFrequency = [];
    foreach ($documents as $doc) {
        $seen = [];
        foreach (array_unique($doc['tokens']) as $token) {
            $seen[$token] = true;
        }
        foreach ($seen as $token => $_) {
            if (!isset($documentFrequency[$token])) {
                $documentFrequency[$token] = 0;
            }
            $documentFrequency[$token] += 1;
        }
    }
    $idf = [];
    foreach ($documentFrequency as $token => $df) {
        $idf[$token] = log(($numDocuments + 1) / ($df + 1)) + 1.0;
    }
    return $idf;
}

function compute_tf(array $tokens): array {
    $tf = [];
    foreach ($tokens as $token) {
        if (!isset($tf[$token])) {
            $tf[$token] = 0;
        }
        $tf[$token] += 1;
    }
    $length = max(1, count($tokens));
    foreach ($tf as $k => $v) {
        $tf[$k] = $v / $length;
    }
    return $tf;
}

function compute_vector(array $tf, array $idf): array {
    $vector = [];
    foreach ($tf as $token => $tfv) {
        $idfv = $idf[$token] ?? 0.0;
        $weight = $tfv * $idfv;
        if ($weight !== 0.0) {
            $vector[$token] = $weight;
        }
    }
    return $vector;
}

function vector_norm(array $vector): float {
    $sum = 0.0;
    foreach ($vector as $w) {
        $sum += $w * $w;
    }
    return sqrt($sum);
}

function cosine_similarity(array $a, array $b): float {
    $dot = 0.0;
    $lenA = vector_norm($a);
    $lenB = vector_norm($b);
    if ($lenA == 0.0 || $lenB == 0.0) {
        return 0.0;
    }
    $small = count($a) < count($b) ? $a : $b;
    $large = $small === $a ? $b : $a;
    foreach ($small as $token => $wa) {
        if (isset($large[$token])) {
            $dot += $wa * $large[$token];
        }
    }
    return $dot / ($lenA * $lenB);
}

function name_bonus(string $query, string $name): float {
    $q = normalize_text($query);
    $n = normalize_text($name);
    if ($q === '' || $n === '') { return 0.0; }
    $score = 0.0;
    if (strpos($n, $q) !== false) { $score += 0.5; }
    $lev = levenshtein($q, $n);
    $maxLen = max(1, max(strlen($q), strlen($n)));
    $sim = 1.0 - min(1.0, $lev / $maxLen);
    $score += 0.35 * $sim;
    foreach (tokenize($query) as $qt) {
        if ($qt !== '' && strpos($n, $qt) === 0) { $score += 0.25; }
    }
    return $score;
}

function term_match_bonus(string $query, FantasticBeast $beast): float {
    $qTokens = tokenize($query);
    $desc = normalize_text($beast->getDescription());
    $name = normalize_text($beast->getName());
    
    $score = 0.0;
    
    // Strong boost for exact term matches in name
    foreach ($qTokens as $qt) {
        if ($qt !== '' && strpos($name, $qt) !== false) {
            $score += 1.0;
        }
    }
    
    // Moderate boost for exact term matches in description
    foreach ($qTokens as $qt) {
        if ($qt !== '' && strpos($desc, $qt) !== false) {
            $score += 0.3;
        }
    }
    
    return $score;
}


function semanticSearchBeasts(array $beasts, string $query, int $limit = 50): array {
    $query = trim($query);
    if ($query === '') { return $beasts; }

    $documents = build_corpus_documents($beasts);
    $idf = compute_idf($documents);

    $queryTokens = tokenize($query);
    // Duplicate query tokens for mild boost
    $expandedQueryTokens = [];
    foreach ($queryTokens as $qt) { $expandedQueryTokens[] = $qt; $expandedQueryTokens[] = $qt; }

    $queryVector = compute_vector(compute_tf($expandedQueryTokens), $idf);

    $scored = [];
    foreach ($documents as $doc) {
        /** @var FantasticBeast $beast */
        $beast = $doc['beast'];
        $docVector = compute_vector(compute_tf($doc['tokens']), $idf);
        $score = cosine_similarity($queryVector, $docVector);
        $score += name_bonus($query, $beast->getName());
        $score += term_match_bonus($query, $beast);
        if ($score > 0.0) {
            $scored[] = ['beast' => $beast, 'score' => $score];
        }
    }

    usort($scored, function($a, $b) {
        if ($a['score'] == $b['score']) { return 0; }
        return ($a['score'] > $b['score']) ? -1 : 1;
    });

    $result = [];
    $count = 0;
    foreach ($scored as $row) {
        $result[] = $row['beast'];
        $count += 1;
        if ($count >= $limit) { break; }
    }

    if (empty($result)) {
        $q = normalize_text($query);
        foreach ($beasts as $beast) {
            if (strpos(normalize_text($beast->getName()), $q) !== false) {
                $result[] = $beast;
            }
        }
    }

    return $result;
}

?>
