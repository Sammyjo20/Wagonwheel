<?php

return [

    /*
     * This configuration variable defines if the banner should be rendered
     * at the start of the email content or at the end of the email content.
     *
     * Available values: "start", "end"
     */
    'component_placement' => 'start',


    /*
     * This configuration variable defines how long Wagonwheel should keep
     * the online version of an email in days.
     *
     * The default value is 30 days.
     * To store indefinitely, set this value to: 0.
     */
    'message_expires_in_days' => 30,

];
