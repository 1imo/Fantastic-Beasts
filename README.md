# Fantastic Beasts

A PHP web application for exploring and discovering information about magical creatures from the Fantastic Beasts universe. This application provides a searchable, sortable database of magical beasts with detailed descriptions and classifications.

## Features

- **Browse Beasts**: View all magical creatures with their classifications and descriptions
- **Semantic Search**: Advanced search functionality using TF-IDF (Term Frequency-Inverse Document Frequency) and cosine similarity for intelligent beast discovery
- **Sorting & Filtering**: Multiple sorting options:
  - Alphabetical (A-Z, Z-A)
  - By Classification (1-5, 5-1)
- **Individual Beast Pages**: Detailed pages for each creature
- **SEO Optimized**: Includes structured data (JSON-LD), meta tags, and sitemap generation
- **Docker Support**: Easy deployment using Docker containers

## Technologies

- **PHP 8.2**: Server-side scripting
- **Docker**: Containerization

## Installation

### Using Docker (Recommended)

1. Clone the repository:
```bash
git clone <repository-url>
cd Fantastic-Beasts
```

2. Build the Docker image:
```bash
docker build -t fantastic-beasts .
```

3. Run the container:
```bash
docker run -d -p 8080:80 --name fantastic-beasts fantastic-beasts
```

4. Access the application at `http://localhost:8080`

### Manual Installation

1. Ensure you have PHP 8.2+ and Apache installed
2. Enable Apache modules: `rewrite`, `headers`, `expires`
3. Copy the project files to your web server directory
4. Set proper permissions:
```bash
chown -R www-data:www-data /path/to/Fantastic-Beasts
```

## Usage

### Browsing Beasts

- Visit the homepage to see all beasts
- Use the filter buttons to sort beasts alphabetically or by classification
- Click on any beast card to view detailed information

### Searching

- Use the search functionality to find specific beasts
- The search uses semantic matching, so you can search by:
  - Beast name
  - Characteristics (e.g., "flying", "venomous", "fire")
  - Habitat or location
  - Any descriptive terms

### Beast Classifications

Beasts are classified on a scale of 1-5:
- **1**: Boring
- **2**: Harmless / May be domesticated
- **3**: Competent wizard should cope
- **4**: Dangerous / Requires specialist knowledge
- **5**: Known wizard killer / Impossible to train or domesticate

## Project Structure

```
Fantastic-Beasts/
├── index.php              # Main homepage
├── beast-details.php      # Individual beast detail pages
├── beast.php              # FantasticBeast class definition
├── beast_home.php         # Beast display functions
├── search.php             # Semantic search implementation
├── json.php               # JSON data loading
├── header.php             # Site header/navigation
├── generate-sitemap.php   # Sitemap generation script
├── beasts.json            # Beast data (JSON format)
├── styles.css             # Main stylesheet
├── specific.css           # Beast detail page styles
├── sitemap.xml            # Generated sitemap
└── Dockerfile             # Docker configuration
```

## Search Algorithm

The application implements a custom semantic search algorithm that:

1. **Tokenizes** search queries and beast descriptions
2. **Normalizes** text (lowercase, removes special characters)
3. **Removes stopwords** (common words like "the", "a", "an")
4. **Applies TF-IDF** weighting to find relevant beasts
5. **Uses cosine similarity** to rank results
6. **Applies bonuses** for:
   - Name matches (exact and fuzzy)
   - Term matches in descriptions
   - Query term positions

## Development

### Adding New Beasts

Edit `beasts.json` and add a new beast object:

```json
{
  "name": "Beast Name",
  "classification": 3,
  "description": "Detailed description of the beast..."
}
```

### Regenerating Sitemap

Run the sitemap generator:
```bash
php generate-sitemap.php
```

## Author

**1imo**

## License

This project is open source and available for educational purposes.

