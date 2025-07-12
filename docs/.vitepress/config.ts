import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Template v0.1",
  description: "Comprehensive docs for a Laravel + Vue.js project",
  base: '/docs/',
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Examples', link: '/markdown-examples' }
    ],

    sidebar: [
      {
        text: 'Documentation',
        items: [
          { text: 'CMS Documentation', link: '/CMS_DOCUMENTATION' },
          { text: 'Users', link: '/users' },
          { text: 'Roles and Permissions', link: '/roles-and-permissions' },
          { text: 'Activity Logs', link: '/activity-logs' },
        ]
      },
      {
        text: 'Examples',
        items: [
          { text: 'Markdown Examples', link: '/markdown-examples' },
          { text: 'Runtime API Examples', link: '/api-examples' }
        ]
      }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/vuejs/vitepress' }
    ]
  }
})
