<h1><?= $title ?></h1>


<?php $user->getProperties() ?>

<dl>
    <dt>Id:</dt>
    <dd><?= $user->id ?></dd>
    <dt>Akronym:</dt>
    <dd><?= $user->acronym ?></dd>
    <dt>Namn:</dt>
    <dd><?= $user->name ?></dd>
    <dt>E-mail:</dt>
    <dd><?= $user->email ?></dd>
    <dt>Skapad:</dt>
    <dd><?= $user->created ?></dd>
    <dt>Uppdaterad:</dt>
    <dd><?= $user->updated ?></dd>
</dl>
