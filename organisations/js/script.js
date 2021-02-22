(function ($) {
        "use strict";

        $(function () {

            // Grab initial filter if there's a hash on the URL
            const initialFilter = window.location.hash && ('.' + window.location.hash.substr(1)) || '*';

            // Initialize Isotope
            const $container = $('.organisations-grid');
            $container.isotope({
                itemSelector: '.organisations-grid-item',
                filter: initialFilter
            });

            $container.on('hover', '.iso-content', function () {
                $container.isotope('layout');
            });

            // Set up the click event for filtering
            $('.organisation-filters').on('click', 'a', function (event) {
                event.preventDefault();

                const $filter = $(this).attr('data-filter');
                const $displayFilter = $filter.substr(1);

                history.pushState ? history.pushState(null, null, '#' + $displayFilter) : location.hash = $displayFilter;
                $container.isotope({filter: $filter});
            });

            // sometimes rendering fails the first time (parts are overlapped)
            // hence repeat at later time
            $( document ).ready(function() {
                setTimeout(function(){ $container.isotope() }, 1000);
                setTimeout(function(){ $container.isotope() }, 5000);
            });
        });
    }(jQuery)
);

