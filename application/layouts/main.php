<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog.IO — Главная страница</title>
        <link rel="shortcut icon" href="/ui/images/icon.png" />
        <link rel="stylesheet" href="/ui/css/main.css" />
    </head>
    <body>
        <header>
            <h1><a href="/" class="control">Blog.IO</a></h1>
            <nav><a href="/login/" class="control">&#128274; <?= $this->loggedIn ? $this->name : 'Вход' ?></a><?= $this->loggedIn ? null : '<a href="/register/" class="control">&#128142; Регистрация</a>' ?></nav>
        </header>
        <?php
            foreach($this->articles as $article)
            {
                $id = $article->ID;
                $title = $article->Title;
                $text = mb_substr($article->Text, 0, 165) . '...';

                $date = new \DateTime($article->WriteDate);
                $date = $date->format('d.m.Y');

                $authors = join(', ', $article->Authors);
                $category = $article->Category;
                $commentariesNumber = $article->CommentariesNumber;

                echo "<section><article><img src=\"/ui/images/preview{$id}.jpg\" alt=\"{$title}\" /><h2><a href=\"/show/Articles/{$id}/\" class=\"header\">{$title}</a></h2><p>{$text}</p><p>&#128198; <time pubdate>{$date}</time>; &#128204; {$category}; &#128102; {$authors}; &#128172; {$commentariesNumber}</p></article></section>";
            }
        ?>
        <footer>
            <p>Copyright © 2017 Blog.IO</p>
            <p><a href="#" class="control">Вверх &#8593;</a></p>
        </footer>
    </body>
</html>