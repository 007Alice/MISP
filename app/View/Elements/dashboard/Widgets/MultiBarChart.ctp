<table style="border-spacing:0px;">
<?php
    if (!empty($data['logarithmic'])) {
        $max = max($data['logarithmic']);
    } else {
        $max = max($data['data']);
    }
    foreach ($data['data'] as $entry => $count) {
        $value = $count;
        if (!empty($data['logarithmic'])) {
            $value = $data['logarithmic'][$entry];
        }
        echo sprintf(
            '<tr><td style="%s">%s</td><td style="%s">%s</td></tr>',
            'text-align:right;width:33%;',
            h($entry),
            'width:100%',
            sprintf(
                '<div title="%s" style="%s">%s</div>',
                h($entry) . ': ' . h($count),
                sprintf(
                    'background-color:%s; width:%s; color:white; text-align:center;',
                    (empty($data['colours'][$entry]) ? '#0088cc' : h($data['colours'][$entry])),
                    100 * h($value) / $max . '%;'
                ),
                h($count)
            ),
            '&nbsp;'
        );
    }
?>
</table>
