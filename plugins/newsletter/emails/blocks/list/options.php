<?php
/* @var $options array contains all the options the current block we're ediging contains */
/* @var $controls NewsletterControls */
?>

<table class="form-table">
    <tr>
        <th>Bullet</th>
        <td>
            <?php
            $bullets = array(
                '2713'=>'&#x2713;',
                '2714'=>'&#x2714;',
                '25BA'=>'&#x25BA;',
                'd7'=>'&#xd7;',
                'bb'=>'&#xbb;',
                '25c9'=>'&#x25c9;',
                '203A'=>'&#x203A;',
                '25CE'=>'&#x25CE;',
                
                // Arrows
                '2192'=>'&#x2192;',
                '2190'=>'&#x2190;',
                '2191'=>'&#x2191;',
                '2193'=>'&#x2193;',
                
                
                );
                      
                $controls->select('bullet', $bullets);
            ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Bullet color', 'newsletter')?></th>
        <td>
            <?php $controls->color('bullet_color') ?>
        </td>
    </tr>
    <tr>
        <th>Items</th>
        <td>
            <?php
            for ($i = 1; $i <= 10; $i++) {
                $controls->text('text_' . $i, 50);
            }
            ?>
        </td>
    </tr>
    <tr>
        <th>Font family</th>
        <td>
            <?php $controls->css_font_family('font_family') ?>
        </td>
    </tr>
    <tr>
        <th>Font size</th>
        <td>
            <?php $controls->css_font_size('font_size') ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Color', 'newsletter')?></th>
        <td>
            <?php $controls->color('color') ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Background', 'newsletter')?></th>
        <td>
            <?php $controls->color('background') ?>
        </td>
    </tr>
</table>
