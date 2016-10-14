<div class='comment-form'>
    <form method=post>
        <input type=hidden name="redirect" value="">
        <input type=hidden name="page" value="">
        <fieldset>
            <legend>Ställ en fråga</legend>
            <p><label>Rubrik: *<br/><input type='text' name='header' value='' required/></label>
            </p>
            <p><label>Text: *<br/><textarea name='content'
                                                 required></textarea></label></p>
            <form action="">
                <input type="checkbox" name="tag" value="Pokemons">Pokémons<br>
                <input type="checkbox" name="tag" value="Teams">Teams<br>
                <input type="checkbox" name="tag" value="Tips">Tips<br>
                <input type="checkbox" name="tag" value="Gyms">Gyms<br>
                <input type="checkbox" name="tag" value="Items">Items<br>
                <input type="checkbox" name="tag" value="Levels">Levels<br>
                <input type="checkbox" name="tag" value="Medals">Medals
            </form>


            <p class=buttons>
                <input type='submit' name='postForm' value='OK'
                       onClick="this.form.action = ''"/>
                <input type='reset' value='Avbryt'
                       onclick='location.href=""'/>
            </p>

        </fieldset>
    </form>
</div>
