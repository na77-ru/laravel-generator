require ( 'bootstrap' );

$ ( function () {

    $ ( ".check-click" ).click ( function () {

        if (checked = $ ( this ).prop ( 'checked' )) {
            id = $ ( this ).attr ( 'id' );
            $ ( '#' + id + '-radio-2' ).prop ( 'checked', true );
        } else {
            $ ( '#' + id + '-radio-0' ).prop ( 'checked', false );
            $ ( '#' + id + '-radio-1' ).prop ( 'checked', false );
            $ ( '#' + id + '-radio-2' ).prop ( 'checked', false );
        }

    } );

    $ ( ".radio-click" ).click ( function () {

        id = $ ( this ).attr ( 'id' );
        count = id.substring ( id.length - 1, id.length );
        part_id = id.substring ( 0, id.length - 8 );

        if (!$ ( '#' + part_id ).prop ( 'checked' )) {

            $ ( '#' + part_id ).prop ( 'checked', true );
        }

    } );


} );
