export default function ModMenuCollapse(selector){

    // Collect each menu
    const $menu = $(selector);

    // Make each menu button open or close its submenu
    const $buttons = $menu.find('[role="button"][aria-controls]');

    $buttons.click(function(e){
        e.preventDefault();
        e.stopPropagation();

        const $button = $(this);

        // Close
        if( $button.attr('aria-expanded')==='true' ) {

            // collapse all in this and lower levels
            const $item = $button.parent();
            const $parent = $item.parent();

            $parent.children().each((idx, item)=>{
                const $sibling = $(item);

                $sibling.children('a').attr('aria-expanded', 'false');
                $sibling.children('ul.collapse.show').collapse('hide');
                $sibling.removeClass('open');
            });

            // Open
        } else {

            // collapse all siblings and its lower levels
            const $item = $button.parent();
            const $parent = $item.parent();

            $item.children('a').attr('aria-expanded', 'true');
            $item.children('ul.collapse').collapse('show');
            $item.addClass('open');

            $parent.children().each((idx, item)=>{
                const $sibling = $(item);

                if( !$sibling.is($item) ) {
                    $sibling.find('a').attr('aria-expanded', 'false');
                    $sibling.find('ul.collapse.show').collapse('hide');
                    $sibling.removeClass('open');
                }

            });
        }
    })

}