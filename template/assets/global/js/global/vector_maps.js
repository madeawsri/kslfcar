!function (document, window, $) {
    "use strict";
    $('#world-map').vectorMap({
        map: 'world_en',
        color: '#ffffff',
        hoverOpacity: 0.7,
        backgroundColor: '#eaeaea',
        selectedColor: '#087380',
        borderOpacity: 0.25,
        borderColor:'#fff',
        enableZoom: true,
        showTooltip: true,
        values: sample_data,
        scaleColors: ['#C8EEFF', '#087380'],
        normalizeFunction: 'polynomial'
    });
    $('#germany-map').vectorMap({
        map: 'germany_en',
        backgroundColor: '#eaeaea',
        color: '#4d9fae',
        borderOpacity: 0.25,
        selectedColor: '#087380',
        hoverColor: '#087380',
        borderColor:'#fff',
        colors: {
            mo: '#087380',
            fl: '#0fb0c0',
            or: '#0fb0c0'
        },
        onRegionClick: function(element, code, region)
        {
            var message = 'You clicked "'
                + region
                + '" which has the code: '
                + code.toUpperCase();

            alert(message);
        }
    });
    $('#usa-map').vectorMap({
        map: 'usa_en',
        backgroundColor: '#eaeaea',
        color: '#4d9fae',
        selectedColor: '#087380',
        hoverColor: '#087380',
        borderColor:'#fff',
        borderOpacity: 0.25,
        colors: {
            mo: '#087380',
            fl: '#0fb0c0',
            or: '#0fb0c0'
        }
    });
    $('#canada-map').vectorMap({
        map: 'canada_en',
        backgroundColor: '#eaeaea',
        color: '#4d9fae',
        selectedColor: '#087380',
        hoverColor: '#087380',
        borderColor:'#fff',
        borderOpacity: 0.25,
        colors: {
            mo: '#087380',
            fl: '#0fb0c0',
            or: '#0fb0c0'
        }
    });
    $('#algeria-map').vectorMap({
        map: 'dz_fr',
        backgroundColor: '#eaeaea',
        color: '#4d9fae',
        selectedColor: '#087380',
        hoverColor: '#087380',
        borderColor:'#fff',
        borderOpacity: 0.25,
        colors: {
            mo: '#087380',
            fl: '#0fb0c0',
            or: '#0fb0c0'
        }
    });
    $('#turkey-map').vectorMap({
        map: 'turkey',
        backgroundColor: '#eaeaea',
        color: '#4d9fae',
        selectedColor: '#087380',
        hoverColor: '#087380',
        borderColor:'#fff',
        colors: {
            mo: '#087380',
            fl: '#0fb0c0',
            or: '#0fb0c0'
        }
    });
    $('#france-map').vectorMap({
        map: 'france_fr',
        backgroundColor: '#eaeaea',
        color: '#4d9fae',
        selectedColor: '#087380',
        hoverColor: '#087380',
        borderColor:'#fff',
        colors: {
            mo: '#087380',
            fl: '#0fb0c0',
            or: '#0fb0c0'
        }
    });
}(document, window, jQuery);
