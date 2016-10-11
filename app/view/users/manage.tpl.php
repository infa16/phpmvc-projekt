<h1>Användare</h1>

<p><i>Här sker hanteringen av användare.</i></p>

<p><a href='<?= $this->url->create('users/add') ?>'>Skapa användare</a></p>

<p><a href='<?= $this->url->create('users/setup') ?>'>Återställ databasen</a></p>

<label>
    Filter:
    <select id="list-filter">
        <option value="all">Alla</option>
        <option value="active">Aktiva</option>
        <option value="inactive">Inaktiva</option>
        <option value="deleted">Borttagna</option>
    </select>
</label>

<script>
    $(function () {
        $('#list-filter').on('change', function () {
            var filter = $(this).val();
            if (filter) {
                window.location = "<?= $this->url->create("users/manage") ?>/" + filter;
            }
            return false;
        });
    });

    $("#list-filter").val("<?= $filter ?>");
</script>

<?php if (is_array($users)) : ?>
    <table class="user-table">
        <tbody>

        <th class="left">Användare</th>
        <th class="left">Namn</th>
        <th class="center">Aktiv</th>
        <th class="center">Uppdatera</th>
        <th class="center">Ta bort</th>
        <?php foreach ($users as $user) : ?>

            <tr>
                <td class="left"><a href='<?= $this->url->create("users/id/{$user->id}") ?>'><?= $user->acronym ?></a></td>
                <td class="left"><?= $this->textFilter->doFilter($user->name, 'shortcode, markdown') ?></td>
                <td class="center"><?= !empty($user->active) ?
                        '<i class="fa fa-check fa-lg"></i>' : '<i class="fa fa-times fa-lg"></i>' ?></td>
                <?php if ($user->deleted): ?>
                    <td class="center"><a href='<?= $this->url->create("users/undelete/{$user->id}") ?>'>
                            <i class="fa fa-refresh fa-lg"></i>
                        </a></td>
                    <td class="center"><a href='<?= $this->url->create("users/delete/{$user->id}") ?>'>
                            <i class="fa fa-trash fa-lg"></i>
                        </a></td>
                <?php else: ?>
                    <td class="center"><a href='<?= $this->url->create("users/update/{$user->id}") ?>'>
                            <i class="fa fa-pencil fa-lg"></i>
                        </a></td>
                    <td class="center"><a href='<?= $this->url->create("users/softDelete/{$user->id}") ?>'>
                            <i class="fa fa-trash fa-lg"></i>
                        </a></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>


<?php endif; ?>