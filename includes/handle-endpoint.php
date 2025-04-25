<?php

function fg_handle_form_submission($request) {
    $params = $request->get_json_params();

    return new WP_REST_Response([
        'success' => true,
        'received' => $params
    ], 200);
}