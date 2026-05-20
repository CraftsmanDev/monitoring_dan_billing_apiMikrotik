<?php

function custom_pagination($page, $totalPage)
{
    $pagination = '<div class="pagination-container">';
    if ($page > 1) {

        $pagination .= '
            <button class="pagination-btn"
                data-page="'.($page - 1).'">

                <i class="fa-solid fa-angle-left"></i>

            </button>
        ';
    }

    // number
    for ($i = 1; $i <= $totalPage; $i++) {

        $active = ($i == $page)
            ? 'active'
            : '';

        $pagination .= '
            <button class="pagination-btn '.$active.'"
                data-page="'.$i.'">

                '.$i.'

            </button>
        ';
    }

    // next
    if ($page < $totalPage) {

        $pagination .= '
            <button class="pagination-btn"
                data-page="'.($page + 1).'">

                <i class="fa-solid fa-angle-right"></i>

            </button>
        ';
    }

    $pagination .= '</div>';

    return $pagination;
}