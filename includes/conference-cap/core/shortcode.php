<?php

/**
 * Coordinates attributes, assets and the backward-compatible renderer.
 */
final class PWE_Conference_Cap_Shortcode {

    /**
     * Render [pwe_conference_cap].
     *
     * @param array $atts Raw shortcode attributes.
     */
    public function render(array $atts): string {
        $attributes = PWE_Conference_Cap_Attributes::from_shortcode($atts);
        $mode       = PWE_Conference_Cap_Mode_Resolver::resolve_mode($attributes->get('conference_cap_conference_mode'));

        PWE_Conference_Cap_Assets::enqueue_common();
        PWE_Conference_Cap_Assets::enqueue_mode($mode['mode']);

        return PWE_Conference_Cap_Legacy_Renderer::PWEConferenceCapOutput($attributes->to_array());
    }
}

