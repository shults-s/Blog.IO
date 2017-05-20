<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Blog.IO — <?= $this->article->Title ?></title>
        <link rel="shortcut icon" href="/ui/images/icon.png" />
        <link rel="stylesheet" href="/ui/css/show.css" />
    </head>
    <body>
        <header>
            <h1><a href="/" class="control">Blog.IO</a></h1>
            <nav><a href="/login/" class="control">&#128274; <?= $this->loggedIn ? $this->name : 'Вход' ?></a><?= $this->loggedIn ? null : '<a href="/register/" class="control">&#128142; Регистрация</a>' ?></nav>
        </header>
        <section>
            <?php
                $id = $this->article->ID;
                $title = $this->article->Title;
                $text = str_replace("\n", '</p><p>', $this->article->Text);

                $date = new \DateTime($this->article->WriteDate);
                $date = $date->format('d.m.Y');

                $authors = join(', ', $this->article->Authors);
                $category = $this->article->Category;

                echo "<h2>{$title}</h2><article><img src=\"/ui/images/preview{$id}.jpg\" alt=\"{$title}\" /><p>&#128198; <time pubdate>{$date}</time>; &#128204; {$category}; &#128102; {$authors}</p><p>{$text}</p></article>";
            ?>
        </section>
        <aside>
            <h2>Комментарии (<?= count($this->article->Commentaries) ?>):</h2>
            <?php
                if (count($this->article->Commentaries) != 0)
                {
                    foreach($this->article->Commentaries as $commentary)
                    {
                        $text = $commentary->Text;

                        $date = new \DateTime($commentary->WriteDate);
                        $date = $date->format('d.m.Y');

                        $author = $commentary->Author;

                        echo "<section><h3>&#128198; <time pubdate>{$date}</time>, &#128102; {$author}</h3><p>{$text}</p></section>";
                    }
                }
                else
                {
                    echo '<p class="single">Здесь никто еще не оставил свой комментарий.</p>';
                }
            ?>
        </aside>
        <footer>
            <p>Copyright © 2017 Blog.IO</p>
            <p><a href="#" class="control">Вверх &#8593;</a></p>
        </footer>
    </body>
</html>