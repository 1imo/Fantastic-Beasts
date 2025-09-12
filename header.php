<header>
    <h1>Fantastic Beasts </br> <span>and where to find them</span></h1>
    <form method="get" action="/">
        <input type="text" name="search" id="searchInput" placeholder="Semantic search beasts (name or description)" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>
</header>