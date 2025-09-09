jQuery(document).ready(function ($) {
    $('#bike').addpowerzoom({
        defaultpower: 1.5,
        powerrange: [1.5, 1.5],
        largeimage: null,
        magnifiersize: [200, 200] //<--no comma following last option!
    });
});
