type CMSItemType = 'text' | 'textarea' | 'image' | 'htmleditor' | 'list_table';

interface CMSItem {
  id: string;
  type: CMSItemType;
  label: string;
  description: string;
  rules?: string;
  summary_field?: boolean;
  items?: CMSItem[]; // For nested list_table
}

interface CMSSection {
  id: string;
  label: string;
  description: string;
  items: CMSItem[];
}

interface CMSSchema {
  slug: string;
  label: string;
  show_metadata: boolean | number;
  sections: CMSSection[];
}

export const cmsSchema: CMSSchema[] = [
  {
    slug: 'email_recipients',
    label: 'General: Email Recipients',
    show_metadata: false,
    sections: [
      {
        id: 'section1',
        label: 'Inquiry Email Recipients',
        description: 'Relevant data and other details',
        items: [
          {
            id: 'list',
            type: 'list_table',
            label: 'List of emails',
            description: '',
            items: [
              {
                id: 'email',
                type: 'text',
                label: 'Email Recipient',
                description: '50 characters max',
                rules: 'email|max:50',
              },
            ],
          },
        ],
      },
      {
        id: 'section2',
        label: 'Shipping Inquiry Email Recipients',
        description: 'Relevant data and other details',
        items: [
          {
            id: 'list',
            type: 'list_table',
            label: 'List of emails',
            description: '',
            items: [
              {
                id: 'email',
                type: 'text',
                label: 'Email Recipient',
                description: '50 characters max',
                rules: 'email|max:50',
              },
            ],
          },
        ],
      },
    ],
  },
  {
    slug: 'header_footer',
    label: 'General: Header and Footer',
    show_metadata: true,
    sections: [
      {
        id: 'section1',
        label: 'Header',
        description: 'Relevant data and other details',
        items: [
          {
            id: 'logo',
            type: 'image',
            label: 'Logo',
            description: 'Max file size: 2MB | Dimension: Max-height 65px | File type: webp',
          },
          {
            id: 'btn_text',
            type: 'text',
            label: 'Button Text',
            description: '',
          },
          {
            id: 'btn_link',
            type: 'text',
            label: 'Button Link',
            description: '',
          },
          {
            id: 'content',
            type: 'textarea',
            label: 'Content',
            description: '',
          },
        ],
      },
      {
        id: 'section2',
        label: 'Footer',
        description: 'Relevant data and other details',
        items: [
          {
            id: 'logo',
            type: 'image',
            label: 'Logo',
            description: 'Max file size: 2MB | Dimension: Max-height 65px | File type: webp',
          },
          {
            id: 'content',
            type: 'htmleditor',
            label: 'Content',
            description: '',
          },
          {
            id: 'copyright',
            type: 'text',
            label: 'Copyright',
            description: '',
          },
        ],
      },
      {
        id: 'section3',
        label: 'Social Media',
        description: 'Relevant data and other details',
        items: [
          {
            id: 'list',
            type: 'list_table',
            label: 'List of Social Media Accounts',
            description: '',
            items: [
              {
                id: 'icon',
                label: 'Icon',
                type: 'image',
                description: 'Max file size: 2MB | Dimension: Max-height 24px | File type: webp',
                rules: 'required',
                summary_field: true,
              },
              {
                id: 'link',
                label: 'Link',
                type: 'text',
                description: 'Max file size: 2MB | Dimension: Max-height 24px | File type: webp',
                rules: 'required',
                summary_field: true,
              },
            ],
          },
        ],
      },
    ],
  },
  {
    slug: 'home',
    label: 'Website: Home Page',
    show_metadata: 1,
    sections: [
      {
        id: 'section1',
        label: 'Section 1: Banner',
        description: 'Relevant data and other details',
        items: [
          {
            id: 'list',
            type: 'list_table',
            label: 'List of Slides',
            description: '',
            items: [
              {
                id: 'title',
                type: 'text',
                label: 'Title',
                description: '',
                rules: 'required',
                summary_field: true,
              },
              {
                id: 'content',
                type: 'textarea',
                label: 'Content',
                description: '',
              },
              {
                id: 'image',
                label: 'Image',
                type: 'image',
                rules: 'required',
                description: 'Max file size: 2MB | Dimension: 1440px x 810px | File type: webp',
                summary_field: true,
              },
              {
                id: 'btn_text',
                type: 'text',
                label: 'Button Text',
                description: '',
              },
              {
                id: 'btn_link',
                type: 'text',
                label: 'Button Link',
                description: '',
              },
            ],
          },
        ],
      },
      {
        id: 'section2',
        label: 'Section 2: Information',
        description: 'Relevant data and other details',
        items: [
          {
            id: 'title',
            type: 'text',
            label: 'Title',
            description: '',
          },
          {
            id: 'content',
            type: 'htmleditor',
            label: 'Content',
            description: '',
          },
          {
            id: 'image',
            type: 'image',
            label: 'Image',
            description: 'Max file size: 2MB | Dimension: 568px x 456px | File type: webp',
          },
        ],
      },
    ],
  },
  {
    slug: 'privacy_policy',
    label: 'General: Privacy Policy Page',
    show_metadata: true,
    sections: [
      {
        id: 'section1',
        label: 'Privacy Policy',
        description: 'Relevant data and other details',
        items: [
          {
            id: 'title',
            type: 'text',
            label: 'Title',
            description: '50 characters max',
            rules: 'required|max:50',
          },
          {
            id: 'date',
            type: 'text',
            label: 'Effectivity Date',
            description: '',
          },
          {
            id: 'content',
            type: 'htmleditor',
            label: 'Content',
            description: '',
          },
        ],
      },
    ],
  },
  {
    slug: 'terms_and_conditions',
    label: 'General: Terms and Conditions Page',
    show_metadata: true,
    sections: [
      {
        id: 'section1',
        label: 'Terms and Conditions',
        description: 'Relevant data and other details',
        items: [
          {
            id: 'title',
            type: 'text',
            label: 'Title',
            description: '50 characters max',
            rules: 'required|max:50',
          },
          {
            id: 'date',
            type: 'text',
            label: 'Effectivity Date',
            description: '',
          },
          {
            id: 'content',
            type: 'htmleditor',
            label: 'Content',
            description: '',
          },
        ],
      },
    ],
  },
];
