<?php

return [
    /*
     * Settings
     */
    'show_warnings' => false,   // Throw an Exception on warnings from dompdf
    'public_path' => null,      // Override the public path if needed

    /*
     * Orientation and paper size
     */
    'orientation' => 'portrait',
    'defines' => [
        /*
         * The location of the DOMPDF font directory
         */
        'font_dir' => storage_path('fonts/'), // advised by dompdf (https://github.com/dompdf/dompdf/pull/782)

        /*
         * The location of the DOMPDF font cache directory
         */
        'font_cache_dir' => storage_path('fonts/'),

        /*
         * The location of a temporary directory.
         */
        'temp_dir' => sys_get_temp_dir(),

        /*
         * dompdf's "chroot"; limits the places dompdf can read files from
         */
        'chroot' => realpath(base_path()),

        /*
         * Whether to enable font subsetting or not.
         */
        'enable_font_subsetting' => false,

        /*
         * The PDF rendering backend to use
         */
        'pdf_backend' => 'CPDF',

        /*
         * PDFlib license key, used with PDFlib backend
         */
        'pdftk' => false,

        /*
         * Whether to enable inline PHP
         */
        'enable_php' => false,

        /*
         * Whether to enable inline Javascript
         */
        'enable_javascript' => true,

        /*
         * Whether to enable remote file access
         */
        'enable_remote' => true,

        /*
         * A ratio applied to the fonts height to be more like browsers' line height
         */
        'font_height_ratio' => 1.1,

        /*
         * Use the more-than-experimental HTML5 Lib parser
         */
        'enable_html5_parser' => true,
    ],
];
