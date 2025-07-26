<?php

return [
    'schema' =>  [
        [
            'id' => 6,
            'slug' => 'home',
            'label' => 'Web: Home Page',
            'show_metadata' => 1,
            'sections' => [
                [
                    'id' => 'section1',
                    'label' => 'Section 1',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'carousel',
                            'type' => 'list_table',
                            'label' => 'Hero Banners',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'image',
                                    'label' => 'Image',
                                    'type' => 'image',
                                    'description' => 'Max file size: 2MB | Dimension: 1440px by 810px',
                                    'rules' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'title',
                                    'label' => 'Title',
                                    'type' => 'text',
                                    'rules' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'description',
                                    'label' => 'Description',
                                    'type' => 'textarea',
                                    'rules' => '',
                                    'summary_field' => false,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 7,
            'slug' => 'events',
            'label' => 'Web:  Events',
            'show_metadata' => 1,
            'sections' => [
                [
                    'id' => 'section1',
                    'label' => 'Section 1',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'description',
                            'type' => 'textarea',
                            'label' => 'Description',
                            'description' => '',
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 8,
            'slug' => 'privacy-policy',
            'label' => 'Web: Privacy Policy Page',
            'show_metadata' => 1,
            'sections' => [
                [
                    'id' => 'section1',
                    'label' => 'Privacy Policy',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'content',
                            'type' => 'htmleditor',
                            'label' => 'Content',
                            'description' => '',
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 9,
            'slug' => 'terms-and-conditions',
            'label' => 'Web: Terms and Conditions Page',
            'show_metadata' => 1,
            'sections' => [
                [
                    'id' => 'section1',
                    'label' => 'Terms and Conditions',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'content',
                            'type' => 'htmleditor',
                            'label' => 'Content',
                            'description' => '',
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 10,
            'slug' => 'header-footer',
            'label' => 'Web: Header and Footer',
            'show_metadata' => 0,
            'sections' => [
                [
                    'id' => 'section1',
                    'label' => 'Header',
                    'description' => '',
                    'items' => [
                        [
                            'id' => 'logo',
                            'type' => 'image',
                            'label' => 'Logo',
                            'description' => 'Max file size: 2MB | Dimension: Max height: 32px',
                        ],
                        [
                            'id' => 'menus',
                            'type' => 'list_table',
                            'label' => "Navigation Menu's",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'text',
                                    'label' => 'Item',
                                    'type' => 'text',
                                    'rules' => 'required',
                                    'summary_field' => true,
                                ],
                                  [
                                    'id' => 'link',
                                    'label' => 'Url',
                                    'type' => 'text',
                                    'rules' => 'required',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section2',
                    'label' => 'Footer: Column 1',
                    'description' => '',
                    'items' => [
                        [
                            'id' => 'header',
                            'type' => 'text',
                            'label' => 'Header',
                            'description' => '',
                        ],
                        [
                            'id' => 'subcontent',
                            'type' => 'textarea',
                            'label' => 'Subcontent',
                            'description' => '',
                        ],
                        [
                            'id' => 'google_link',
                            'type' => 'text',
                            'label' => 'Play Store Download Link',
                            'description' => '',
                        ],
                    ],
                ],
                [
                    'id' => 'section3',
                    'label' => 'Footer: Column 2',
                    'description' => '',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'links',
                            'type' => 'list_table',
                            'label' => 'Links',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'text',
                                    'label' => 'Text',
                                    'type' => 'text',
                                    'rules' => 'required',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'url',
                                    'label' => 'Link',
                                    'type' => 'text',
                                    'rules' => 'required',
                                    'summary_field' => false,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section4',
                    'label' => 'Footer: Column 3',
                    'description' => '',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'social_accounts',
                            'type' => 'list_table',
                            'label' => 'Social Media Accounts',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'icon',
                                    'label' => 'Icon',
                                    'type' => 'image',
                                    'rules' => '',
                                    'description' => 'Max file size: 2MB | Dimension: 24px x 24px',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'link',
                                    'label' => 'Link',
                                    'type' => 'text',
                                    'rules' => 'required',
                                    'description' => '',
                                    'summary_field' => false,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section5',
                    'label' => 'Footer: Bottom Part',
                    'description' => '',
                    'items' => [
                        [
                            'id' => 'links',
                            'type' => 'list_table',
                            'label' => 'Links',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'url',
                                    'label' => 'Link',
                                    'type' => 'text',
                                    'rules' => 'required',
                                    'summary_field' => false,
                                ],
                                [
                                    'id' => 'text',
                                    'label' => 'Text',
                                    'type' => 'text',
                                    'rules' => 'required',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                        [
                            'id' => 'logo',
                            'type' => 'image',
                            'label' => 'Logo',
                            'description' => 'Max file size: 2MB | Dimension: Max height: 32px',
                        ],
                        [
                            'id' => 'copyright',
                            'type' => 'text',
                            'label' => 'Copyright',
                            'description' => '',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
