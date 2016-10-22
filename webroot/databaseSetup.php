<?php

$app->db->dropTableIfExists('Question')->execute();
$app->db->createTable(
    'Question',
    [
        'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
        'Title' => ['varchar(100)', 'not null'],
        'Content' => ['text', 'not null'],
        'CreatedBy' => ['integer', 'not null'],
        'CreatedTime' => ['datetime', 'not null']
    ]
)->execute();

$app->db->dropTableIfExists('Answer')->execute();
$app->db->createTable(
    'Answer',
    [
        'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
        'QuestionId' => ['integer', 'not null'],
        'Content' => ['text', 'not null'],
        'CreatedBy' => ['integer', 'not null'],
        'CreatedTime' => ['datetime', 'not null']
    ]
)->execute();

$app->db->dropTableIfExists('Comment')->execute();
$app->db->createTable(
    'Comment',
    [
        'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
        'ReferenceId' => ['integer', 'not null'], //Question.Id or Answer.Id
        'ReferenceType' => ['integer', 'not null'], //1 = Question; 2 = Answer
        'Content' => ['text', 'not null'],
        'CreatedBy' => ['integer', 'not null'],
        'CreatedTime' => ['datetime', 'not null'],
    ]
)->execute();

$app->db->dropTableIfExists('User')->execute();
$app->db->createTable(
    'User',
    [
        'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
        'Username' => ['varchar(20)', 'not null'],
        'Name' => ['varchar(80)', 'not null'],
        'Email' => ['varchar(80)', 'not null'],
        'Password' => ['varchar(255)', 'not null'],
        'CreatedTime' => ['datetime', 'not null']
    ]
)->execute();

$app->db->dropTableIfExists('Tag')->execute();
$app->db->createTable(
    'Tag',
    [
        'Id' => ['integer', 'primary key', 'not null', 'auto_increment'],
        'Tag' => ['varchar(80)', 'not null'],
        'Description' => ['varchar(255)', 'not null']
    ]
)->execute();

$app->db->dropTableIfExists('QuestionTagLink')->execute();
$app->db->createTable(
    'QuestionTagLink',
    [
        'QuestionId' => ['integer', 'not null'],
        'TagId' => ['integer', 'not null'],
        'PRIMARY KEY (QuestionId, TagId)' => []
    ]
)->execute();

$app->db->insert(
    'User',
    ['Username', 'Name', 'Email', 'Password', 'CreatedTime']
);

$now = gmdate('Y-m-d H:i:s');

$app->db->execute([
    'admin',
    'Administrator',
    'admin@dbwebb.se',
    password_hash('admin', PASSWORD_DEFAULT),
    '2016-08-30 09:09:09'
]);
$adminId = $app->db->lastInsertId();

$app->db->execute([
    'doe',
    'Jane Doe',
    'doe@dbwebb.se',
    password_hash('doe', PASSWORD_DEFAULT),
    '2016-09-01 10:09:09'
]);
$doeId = $app->db->lastInsertId();

$app->db->execute([
    'professor',
    'Professor Willow',
    'professor@pokemon.go',
    password_hash('professor', PASSWORD_DEFAULT),
    '2016-09-11 14:09:09'
]);
$willowId = $app->db->lastInsertId();

$app->db->execute([
    'spark',
    'Spark Instinct',
    'spark@pokemon.go',
    password_hash('spark', PASSWORD_DEFAULT),
    '2016-09-02 17:09:09'
]);
$sparkId = $app->db->lastInsertId();

$app->db->execute([
    'candela',
    'Candela Valor',
    'candela@pokemon.go',
    password_hash('candela', PASSWORD_DEFAULT),
    '2016-09-18 15:09:09'
]);
$candelaId = $app->db->lastInsertId();

$app->db->execute([
    'blanche',
    'Blanche Mystic',
    'blanche@pokemon.go',
    password_hash('blanche', PASSWORD_DEFAULT),
    '2016-09-15 16:09:09'
]);
$blancheId = $app->db->lastInsertId();

$app->db->insert(
    'Question',
    ['Title', 'Content', 'CreatedBy', 'CreatedTime']
);

$app->db->execute([
    'Hur vet jag vilken evolution Eevee får?',
    'Kan jag på något sätt bestämma vilken typ Eevee blir efter att jag har evolvat den?',
    $adminId,
    '2016-10-01 01:01:01'
]);
$questionEvolutionId = $app->db->lastInsertId();

$app->db->execute([
    'Vilken är den bästa att ha som Buddy?',
    'Vilken pokemon ska jag välja som buddy? Får man olika mycket godis beroende på hur mycket jag går?',
    $doeId,
    '2016-10-03 14:09:09'
]);
$questionBuddyId = $app->db->lastInsertId();

$app->db->execute([
    'Vilket lag ska jag välja?',
    'Snart har jag levlat upp tillräckligt för att kunna välja lag. Vilket lag är bäst? Vad finns det för olika fördelar
        med de olika lagen? Spelar det egentligen någon roll vilket jag väljer?',
    $doeId,
    '2016-10-05 07:09:09'
]);
$questionTeamId = $app->db->lastInsertId();

$app->db->execute([
    'Vad har jag medaljerna till?',
    'Visst är det coolt att få en massa medaljer för saker man klarat av. Och för att hålla reda hur långt man har kommit i spelet,
        förutom sin level och xp då. Att se hur långt man har gått tycker jag är intressant, hur många Pokémons jag fångat
        och hur många ägg jag lyckats kläcka. Men är medaljerna bra för något, eller är det bara en kul grej?',
    $doeId,
    '2016-10-07 10:04:09'
]);
$questionMedalsId = $app->db->lastInsertId();

$app->db->execute([
    'Varför ska jag gymma?',
    'Jag undrar varför jag ska gymma. Vad är det bra för och ger det något tillbaka?
        Vad händer om mina Pokémons blir besegrade? Försvinner de då? Är det här jag har användning för alla såna där
        potions som man får ifrån PokeStopen?
        Jag är egentligen mest intresserad av att utforska och fånga Pokémons...',
    $willowId,
    '2016-10-09 12:09:09'
]);
$questionGymsId = $app->db->lastInsertId();

$app->db->execute([
    'Varför har jag skuggor i min Pokedex?',
    'Jag undrar vad det är för skuggor som jag ser i min Pokedex. De som jag har fångat, kläckt eller evolvat syns ju som
    vanliga bilder. På de jag inte har fångat, kläckt eller evolvat fram syns bara en siffra som motsvarar en plats i Pokedexet.
     Varifrån kommer alla skuggor?',
    $adminId,
    '2016-10-11 05:05:09'
]);
$questionShadowId = $app->db->lastInsertId();

$app->db->insert(
    'Answer',
    ['QuestionId', 'Content', 'CreatedBy', 'CreatedTime']
);

$app->db->execute([
    $questionEvolutionId,
    'Det går att döpa om Eevee innan du evolvar den. Använd Rainer (för Vapoeron), Sparky (för Jolteon) eller Pyro
         (för Flareon) beroende på vilken evolution du är ute efter.',
    $doeId,
    '2016-10-01 10:09:09'
]);
$evolutionAnswerId = $app->db->lastInsertId();

$app->db->execute([
    $questionBuddyId,
    'Du kan välja vilket pokemon du vill som buddy. Det finns tre olika sträckor, 1, 3 och 5km som man behöver gå 
        för att få godis. Oavsett sträcka får du bara en godis.',
    $willowId,
    '2016-10-04 01:09:09'
]);

$app->db->execute([
    $questionBuddyId,
    'Om du har en favorit-pokemon kan du naturligtvis välja den. Har du någon pokemon du behöver godis till för att tex evolva så kan du ta den.',
    $adminId,
    '2016-10-04 10:21:09'
]);

$app->db->execute([
    $questionTeamId,
    'Självklart ska du välja **Team Instinct**, något annat är bara trams. Du litar på din instinkt helt enkelt.
        Och så har vi ju den legendariska *Zapdos* som maskot, bara det liksom...!',
    $sparkId,
    '2016-10-05 12:09:09'
]);

$app->db->execute([
    $questionTeamId,
    'Om du är vetenskapligt lagd och intresserad av vetenskap ska du välja **Team Mystic**. Vi blandar inte in känslor
        i det hela, allt handlar om kunskap om hur Pokémons fungerar. När man forskar behöver man vara cool, vilket 
        stärks av vår maskot, den legendariska (isfågeln) *Articuno*.',
    $blancheId,
    '2016-10-05 13:09:09'
]);

$app->db->execute([
    $questionTeamId,
    'Utan tvekan **Team Valor**! Att lära känna sina Pokémons, deras styrkor och svagheter, är det enda som är viktigt!För att bli den bästa
         tränaren måste du veta vad som funkar och inte mot de motståndare du kommer möta. För att bli bäst måste man brinna.
         Vår maskot är den legendariska eldfågeln *Moltres* och vi brinner för vår sak!',
    $candelaId,
    '2016-10-05 12:19:09'
]);

$app->db->execute([
    $questionMedalsId,
    'Från början var medaljerna bara en utsmycknig och en rolig grej, men nu har de faktiskt en innebörd!
        Nu finns det något som kallas *fångstbonus*, vilket innebär att ju fler Pokémons av en viss typ du fångar, desto
        lättare blir det att fånga Pokémons av den typen.',
    $candelaId,
    '2016-10-08 13:13:09'
]);
$medalsAnswerOneId = $app->db->lastInsertId();

$app->db->execute([
    $questionGymsId,
    'I Pokémon-världen är "battles" centrala, det är vad Pokémons gör. Som tränare samlar man på Pokémons och de
        battlar på gymmet. Du som tränare tjänar XP och kan också få PokeCoins och Stardust genom att slå ner ett gym.',
    $candelaId,
    '2016-10-10 10:10:09'
]);
$gymsAnswerOneId = $app->db->lastInsertId();

$app->db->execute([
    $questionGymsId,
    'Som spelare handlar det om att öka sin XP på olika sätt. Att slå ner ett gym som tillhör ett annat lag ger XP.
        Slår du ut alla Pokémons på gymmet kan du sätta in en av dina egna och då kan du få coins och Stardust som *defenderbonus*.',
    $doeId,
    '2016-10-11 12:11:09'
]);
$gymsAnswerTwoId = $app->db->lastInsertId();

$app->db->execute([
    $questionGymsId,
    'Ser att ingen har svarat på hela frågan än...
        Om du gymmar och dina Pokémons blir besegrade så förlorar du dem inte, de svimmar av och du måste återuppliva dem
        för att kunna gymma igen. Det är där du använder dina revives och potions. 
        Som tidigare nämnt är det för att tjäna XP, coins och Stardust som man gymmar. Du kan aldrig förlora poäng, det enda som
        händer är att dina Pokémons behöver livas upp igen om du förlorar.',
    $adminId,
    $now
]);

$app->db->execute([
    $questionShadowId,
    'Det beror på att du har försökt fånga dem, men inte lyckats, alltså de smet.    
    Ett nytt fenomen är också att man får Pokémonens skugga i sitt Pokedex när man har sett den på ett gym. 
    Du behöver alltså inte ha sett en "vild" Pokémon för att det ska räknas som att du har sett den.',
    $willowId,
    '2016-10-11 15:15:09'
]);
$shadowAnswerOneId = $app->db->lastInsertId();

$app->db->insert(
    'Comment',
    ['ReferenceId', 'ReferenceType', 'Content', 'CreatedBy', 'CreatedTime']
);

$app->db->execute([
    $evolutionAnswerId,
    2,
    'Första gången du använder tricket med att döpa om Eevee funkar det, försöker du igen blir det en slumpmässigt vald evolution.',
    $sparkId,
    '2016-10-01 19:09:09'
]);

$app->db->execute([
    $questionTeamId,
    1,
    'Handlar det inte bara om att välja sin favoritfärg?',
    $adminId,
    '2016-10-05 07:46:09'
]);

$app->db->execute([
    $questionTeamId,
    1,
    'Du kan fundera på om du föredrar is-, eld- eller electric-typ Pokémons, lagen har ju olika maskotar.',
    $willowId,
    '2016-10-06 12:59:09'
]);

$app->db->execute([
    $medalsAnswerOneId,
    2,
    'Alla medaljer ger inte bonus. De är uppdelade i "traditionella" medaljer, som fortfarande mäter hur långt du har 
        gått eller hur många *tiny Rattata* du har fångat, samt "typ-medaljer" som ger fångstbonus.',
    $adminId,
    $now
]);

$app->db->execute([
    $questionGymsId,
    1,
    'Klart att du inte behöver gymma, är du bara intresserad av att catch´em all, så gör det!',
    $blancheId,
    '2016-10-09 21:09:09'
]);

$app->db->execute([
    $gymsAnswerOneId,
    2,
    'Att gymma är ju grymt kul! Vi måste hålla ihop som ett team och ta över alla gym. Go Instinct!',
    $sparkId,
    '2016-10-05 12:09:09'
]);

$app->db->execute([
    $gymsAnswerTwoId,
    2,
    'Det är naturligtvis upp till dig om du bara vill samla och utforska. Du väljer hur du vill spela!',
    $adminId,
    '2016-10-15 12:09:09'
]);

$app->db->execute([
    $questionShadowId,
    1,
    'Det finns en annan typ av skuggor också, de du ser på Sightings.',
    $blancheId,
    '2016-10-15 11:10:09'
]);

$app->db->execute([
    $shadowAnswerOneId,
    2,
    'Skuggorna du ser på Sightings beror på att du inte har fångat just den Pokémonen än. Du kan ha den som 
    skugga i ditt Pokedex, men det syns inte vilken det är förrän du har fångat, kläckt eller evolvat fram den.',
    $candelaId,
    '2016-10-15 11:10:09'
]);

$app->db->insert(
    'Tag',
    ['Tag', 'Description']
);

$app->db->execute([
    'Pokémons',
    'Om olika Pokémons'
]);
$tagPokemons = $app->db->lastInsertId();

$app->db->execute([
    'Teams',
    'Om de olika lagen, Team Valor, Mystic och Instinct'
]);
$tagTeams = $app->db->lastInsertId();

$app->db->execute([
    'Tips',
    'Om hur man kan spela effektivare, levla upp snabbare eller hitta bättre Pokémons'
]);
$tagTips = $app->db->lastInsertId();

$app->db->execute([
    'Gyms',
    'Om att träna upp sitt eget gym eller ta ner ett annat lags gym'
]);
$tagGyms = $app->db->lastInsertId();

$app->db->execute([
    'Items',
    'Om de olika sakerna man kan få i spelet. Bollar, potions, incense och lucky eggs mm'
]);
$tagItems = $app->db->lastInsertId();

$app->db->execute([
    'Levels',
    'Om nivåerna i spelet'
]);
$tagLevels = $app->db->lastInsertId();

$app->db->execute([
    'Medals',
    'Om medaljerna och bonussystemet'
]);
$tagMedals = $app->db->lastInsertId();

$app->db->insert(
    'QuestionTagLink',
    ['QuestionId', 'TagId']
);

$app->db->execute([
    $questionEvolutionId,
    $tagPokemons
]);

$app->db->execute([
    $questionBuddyId,
    $tagPokemons
]);

$app->db->execute([
    $questionBuddyId,
    $tagTips
]);

$app->db->execute([
    $questionTeamId,
    $tagTeams
]);

$app->db->execute([
    $questionTeamId,
    $tagTips
]);

$app->db->execute([
    $questionTeamId,
    $tagLevels
]);

$app->db->execute([
    $questionMedalsId,
    $tagMedals
]);

$app->db->execute([
    $questionMedalsId,
    $tagTips
]);

$app->db->execute([
    $questionGymsId,
    $tagTips
]);
$app->db->execute([
    $questionGymsId,
    $tagGyms
]);

$app->db->execute([
    $questionGymsId,
    $tagItems
]);

$app->db->execute([
    $questionShadowId,
    $tagPokemons
]);

$app->db->execute([
    $questionShadowId,
    $tagTips
]);
