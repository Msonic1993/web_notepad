<div>
    <h3> Edutuj notatkę </h3>
    <div>
        <?php $note = ($params['note']); ?>
        <form class="note-form" action="/src/?action=edit" method="post">
            <ul>
                <li>
                    <input type="hidden" name="id" class="field-long" value="<?php echo $note['id'] ?> ?>" />
                    <label>Tytuł <span class="required">*</span></label>
                    <input type="text" name="title" class="field-long" value="<?php echo $note['title'] ?>" />
                </li>
                <li>
                    <label>Treść</label>
                    <textarea name="description" id="field5" class="field-long field-textarea"> <?php echo $note['description'] ?></textarea>
                </li>
                <li>
                    <input type="submit" value="Submit" />
                </li>
            </ul>
        </form>
    </div>
</div>
