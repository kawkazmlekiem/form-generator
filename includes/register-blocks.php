<?php

function fg_register_blocks() {
    register_block_type(
        FG_PLUGIN_DIR . 'build/block.json',
        [
            'render_callback' => 'fg_form_generator_render' 
        ]
    );
}