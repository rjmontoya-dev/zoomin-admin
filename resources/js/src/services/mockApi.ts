// src/services/mockApi.ts

// A simple data formatter
const formatDate = (date: Date) => {
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  }).format(date);
};

const articles = [
  { id: 1, title: 'Mastering Vue 3 Composition API', author: 'Jane Doe', status: 'Published', dateCreated: formatDate(new Date('2025-06-01')), published: true },
  { id: 2, title: 'Laravel 11: What’s New?', author: 'John Smith', status: 'Draft', dateCreated: formatDate(new Date('2025-05-28')), published: false },
  { id: 3, title: 'Understanding JavaScript Closures', author: 'Alice Johnson', status: 'Published', dateCreated: formatDate(new Date('2025-05-25')), published: true },
  { id: 4, title: 'Why You Should Learn Rust in 2025', author: 'Mark Brown', status: 'Archived', dateCreated: formatDate(new Date('2025-05-20')), published: false },
  { id: 5, title: 'Optimizing React Native Performance', author: 'Emma Davis', status: 'Published', dateCreated: formatDate(new Date('2025-05-15')), published: true },
  { id: 6, title: 'Nuxt 4 vs Next.js: A 2025 Comparison', author: 'Liam Wilson', status: 'Draft', dateCreated: formatDate(new Date('2025-05-12')), published: false },
  { id: 7, title: 'Building Scalable APIs with Laravel', author: 'Chloe Martinez', status: 'Published', dateCreated: formatDate(new Date('2025-05-10')), published: true },
  { id: 8, title: 'Dark Mode in Tailwind CSS', author: 'Ethan Anderson', status: 'Draft', dateCreated: formatDate(new Date('2025-05-05')), published: false },
  { id: 9, title: 'Understanding TypeScript Generics', author: 'Mia Clark', status: 'Published', dateCreated: formatDate(new Date('2025-05-02')), published: true },
  { id: 10, title: 'Deploying Laravel Apps on Docker', author: 'Noah Lewis', status: 'Archived', dateCreated: formatDate(new Date('2025-04-29')), published: false },
];

export { articles }
