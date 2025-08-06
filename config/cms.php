<?php

return [
    'schema' =>  [
        [
            'id' => 1,
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
                            'id' => 'banner',
                            'type' => 'image',
                            'label' => 'Banner',
                            'description' => 'Max file size: 2MB | Dimension: Max height: 32px',
                        ],
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                         [
                            'id' => 'description',
                            'type' => 'text',
                            'label' => 'Description',
                            'description' => '',
                            'summary_field' => true,
                        ],
                    ],
                ],
                [
                    'id' => 'section2',
                    'label' => 'Section 2',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'ratings',
                            'type' => 'list_table',
                            'label' => 'Services Ratings',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'rating',
                                    'label' => 'Rating',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                                [
                                    'id' => 'title',
                                    'label' => 'Title',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                            ]
                        ],
                    ],
                ],
                [
                    'id' => 'section3',
                    'label' => 'Section 3',
                    'description' => 'Relevant data and other details',
                    'items' => [
                         [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'descriptions',
                            'type' => 'text',
                            'label' => 'Description',
                            'description' => '',
                        ],
                        [
                            'id' => 'core_services',
                            'type' => 'list_table',
                            'label' => 'Core Services',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'title',
                                    'label' => 'Title',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                                [
                                    'id' => 'description',
                                    'label' => 'Description',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                            ]
                        ],
                        [
                            'id' => 'button',
                            'type' => 'text',
                            'label' => 'Button Text',
                            'description' => '',
                        ],
                        [
                            'id' => 'link',
                            'type' => 'text',
                            'label' => 'Button Link',
                            'description' => '',
                        ],
                    ],
                ],
                [
                    'id' => 'section4',
                    'label' => 'Section 4',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'descriptions',
                            'type' => 'text',
                            'label' => 'Description',
                            'description' => '',
                        ],
                        [
                            'id' => 'testimonials_items',
                            'type' => 'list_table',
                            'label' => 'Testimonials',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'star_count',
                                    'label' => 'Star Count',
                                    'type' => 'text',
                                    'rules' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'description',
                                    'label' => 'Description',
                                    'type' => 'text',
                                    'rules' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'name',
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'rules' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'job',
                                    'label' => 'Job Title',
                                    'type' => 'text',
                                    'rules' => '',
                                    'summary_field' => true,
                                ],
                            ]
                        ],
                    ],
                ],
                [
                    'id' => 'section5',
                    'label' => 'Section 5',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'descriptions',
                            'type' => 'text',
                            'label' => 'Description',
                            'description' => '',
                        ],
                        [
                            'id' => 'button',
                            'type' => 'text',
                            'label' => 'Button',
                            'description' => '',
                        ],
                        [
                            'id' => 'link',
                            'type' => 'text',
                            'label' => 'Button Link',
                            'description' => '',
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 2,
            'slug' => 'about',
            'label' => 'Web:  About',
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
                        [
                            'id' => 'title_story',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => 'Our Story',
                        ],
                        [
                            'id' => 'description_story',
                            'type' => 'textarea',
                            'label' => 'Description',
                            'description' => 'Our story description',
                        ],
                        [
                            'id' => 'ratings',
                            'type' => 'list_table',
                            'label' => 'Services Ratings',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'rating',
                                    'label' => 'Rating',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                                [
                                    'id' => 'title',
                                    'label' => 'Title',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                            ]
                        ],
                    ],
                ],
                [
                    'id' => 'section2',
                    'label' => 'Section 2',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'mission_vision',
                            'type' => 'list_table',
                            'label' => "Mission and Vision",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,

                                ],
                                [
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'label' => 'Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section3',
                    'label' => 'Section 3',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'core_values',
                            'type' => 'list_table',
                            'label' => "Core Values",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,

                                ],
                                [
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'label' => 'Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section4',
                    'label' => 'Section 4',
                    'description' => 'Relevant data and other details',
                    'items' => [
                         [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'team',
                            'type' => 'list_table',
                            'label' => "Team",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'name',
                                    'type' => 'text',
                                    'label' => 'Name',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                 [
                                    'id' => 'role',
                                    'type' => 'text',
                                    'label' => 'Role',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'experience',
                                    'type' => 'text',
                                    'label' => 'Experience',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'descriptive_role',
                                    'type' => 'textarea',
                                    'label' => 'Descriptive Role',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section5',
                    'label' => 'Section 5',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'journey',
                            'type' => 'list_table',
                            'label' => "Journey",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'label' => 'Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'year',
                                    'type' => 'text',
                                    'label' => 'Year',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section6',
                    'label' => 'Section 6',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'set_apart',
                            'type' => 'list_table',
                            'label' => "Set Us Apart",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'label' => 'Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 3,
            'slug' => 'services',
            'label' => 'Web:  Services',
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
                    ]
                ],
                [
                    'id' => 'section2',
                    'label' => 'Section 2',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'services',
                            'type' => 'list_table',
                            'label' => "List of Services",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,

                                ],
                                [
                                    'id' => 'subtitle',
                                    'type' => 'text',
                                    'label' => 'Sub Title',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'label' => 'Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'features',
                                    'type' => 'textarea',
                                    'label' => 'Key Features',
                                    'description' => 'Separate it with an end line (enter)',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'button',
                                    'type' => 'text',
                                    'label' => 'Button',
                                    'description' => '',
                                ],
                                [
                                    'id' => 'link',
                                    'type' => 'text',
                                    'label' => 'Button Link',
                                    'description' => '',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section3',
                    'label' => 'Section 3',
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
                            'type' => 'text',
                            'label' => 'Description',
                            'description' => '',
                        ],
                        [
                            'id' => 'process',
                            'type' => 'list_table',
                            'label' => "Development Process",
                            'description' => '',
                            'items' => [
                                 [
                                    'id' => 'sequence',
                                    'type' => 'text',
                                    'label' => 'Sequence',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'label' => 'Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 4,
            'slug' => 'projects',
            'label' => 'Web:  Projects',
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
                    ]
                ],
                [
                    'id' => 'section2',
                    'label' => 'Section 2',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'ratings',
                            'type' => 'list_table',
                            'label' => 'Services Ratings',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'rating',
                                    'label' => 'Rating',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                                [
                                    'id' => 'title',
                                    'label' => 'Title',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                                [
                                    'id' => 'description',
                                    'label' => 'Description',
                                    'type' => 'textarea',
                                    'rules' => '',
                                ],
                            ]
                        ],
                    ],
                ],
                [
                    'id' => 'section3',
                    'label' => 'Section 3',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'projects',
                            'type' => 'list_table',
                            'label' => "Development Process",
                            'description' => '',
                            'items' => [
                                 [
                                    'id' => 'icons',
                                    'type' => 'image',
                                    'label' => 'Icons',
                                    'description' => 'Max file size: 2MB | Dimension: Max height: 32px',
                                ],
                                [
                                    'id' => 'badge',
                                    'type' => 'text',
                                    'label' => 'Badge',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'subtitle',
                                    'type' => 'text',
                                    'label' => 'Sub Title',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'label' => 'Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'calendar_date',
                                    'type' => 'text',
                                    'label' => 'Calendar Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'devs',
                                    'type' => 'text',
                                    'label' => 'Developers',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'technologies',
                                    'type' => 'textarea',
                                    'label' => 'Technologies',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'results',
                                    'type' => 'textarea',
                                    'label' => 'Key Results',
                                    'description' => 'Separate it with an end line (enter)',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section4',
                    'label' => 'Section 4',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                       [
                            'id' => 'process',
                            'type' => 'list_table',
                            'label' => "Development Process",
                            'description' => '',
                            'items' => [
                                 [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section5',
                    'label' => 'Section 5',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                       [
                            'id' => 'process',
                            'type' => 'list_table',
                            'label' => "Development Process",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'description',
                                    'type' => 'text',
                                    'label' => 'Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
         [
            'id' => 5,
            'slug' => 'contacts',
            'label' => 'Web:  Contacts',
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
                    ]
                ],
                [
                    'id' => 'section2',
                    'label' => 'Section 2',
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
                        [
                            'id' => 'information',
                            'type' => 'list_table',
                            'label' => 'Contact Information',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'title',
                                    'label' => 'Title',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                                [
                                    'id' => 'subtitle',
                                    'label' => 'Sub Title',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                                [
                                    'id' => 'description',
                                    'label' => 'Description',
                                    'type' => 'textarea',
                                    'rules' => '',
                                ],
                            ]
                        ],
                        [
                            'id' => 'choose_title',
                            'type' => 'text',
                            'label' => 'Choose us Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'items',
                            'type' => 'textarea',
                            'label' => 'Key Items',
                            'description' => 'Separate it with an end line (enter)',
                        ],
                    ],
                ],
                [
                    'id' => 'section3',
                    'label' => 'Section 3',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'faqs',
                            'type' => 'list_table',
                            'label' => "FAQ's",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'title',
                                    'type' => 'text',
                                    'label' => 'Title',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                                [
                                    'id' => 'description',
                                    'type' => 'textarea',
                                    'label' => 'Description',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section4',
                    'label' => 'Section 4',
                    'description' => 'Relevant data and other details',
                    'items' => [
                        [
                            'id' => 'services_offered',
                            'type' => 'list_table',
                            'label' => "Services",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'name',
                                    'type' => 'text',
                                    'label' => 'Name',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                        [
                            'id' => 'budget',
                            'type' => 'list_table',
                            'label' => "Budget",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'name',
                                    'type' => 'text',
                                    'label' => 'Name',
                                    'description' => '',
                                    'summary_field' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 3,
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
            'id' => 4,
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
            'id' => 5,
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
                            'id' => 'logo',
                            'type' => 'image',
                            'label' => 'Logo',
                            'description' => 'Max file size: 2MB | Dimension: Max height: 32px',
                        ],
                        [
                            'id' => 'description',
                            'type' => 'textarea',
                            'label' => 'Tag Line',
                            'description' => '',
                        ],
                        [
                            'id' => 'social_media',
                            'type' => 'list_table',
                            'label' => "Social Media Links",
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'logo',
                                    'type' => 'image',
                                    'label' => 'Logo',
                                    'description' => 'Max file size: 2MB | Dimension: Max height: 32px',
                                ],
                                [
                                    'id' => 'link',
                                    'label' => 'Link',
                                    'type' => 'text',
                                    'rules' => 'required',
                                    'summary_field' => true,
                                ],
                            ],
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
                            'id' => 'services',
                            'type' => 'list_table',
                            'label' => 'Services',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'name',
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section5',
                    'label' => 'Footer: Column 4',
                    'description' => '',
                    'items' => [
                        [
                            'id' => 'title',
                            'type' => 'text',
                            'label' => 'Title',
                            'description' => '',
                        ],
                        [
                            'id' => 'contacts',
                            'type' => 'list_table',
                            'label' => 'Contacts',
                            'description' => '',
                            'items' => [
                                [
                                    'id' => 'name',
                                    'label' => 'Name',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                                [
                                    'id' => 'icon',
                                    'label' => 'Icons',
                                    'type' => 'text',
                                    'rules' => '',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'id' => 'section6',
                    'label' => 'Footer: Bottom Part',
                    'description' => '',
                    'items' => [
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
