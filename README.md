# Webpulse

## Instalace

## React refresher
- npm create vite (react, javascript)
- extension .jsx = having HTML code in javascript files
- where we use components, it is required to start with uppercase character
- if you have multiple elements side by side, it is necessary to wrap them by another element (for example: `<></>`)
- in `return()` we can return only one element
- we can pass function to props same as we can pass numbers and strings
- function starting with *use* are considered as hooks
- `useState()` always returns 2 elements as array (`useState()[0]` = current value, `useState()[1]` = updating value)
- `key` prop should be guaranteed unique for each item
- promises should not be returned
- `useEffect` will be executed by react whenever React thinks it requires execution
- `useEffect` has second parameter **dependencies**
- for routing we need to install `pnpm install react-router-dom`
- react app are rendered on client side

## Next.js
- instalation
- - typescript: no
- - eslint: yes
- - tailwind: no
- - src directory: no
- - App router: yes
- - custom alias: no

## Section 3: NextJS Essentials (App Router)
- Next.js app are rendered on server side
- in file based routing every new route should b*e* named `page.js`
- name of component does not matter
- app router vs pages router (section 1, part 7)
- `page.js`, `layout.js`, `404.js`, `error.js` are reserved names (https://www.udemy.com/course/nextjs-react-the-complete-guide/learn/lecture/41161954#overview)
- react server components: rendered only on server, never on client
- every nextjs project needs at least one root layout
- we can add `layout.js` in route folders
- `export const metadata = {...}`
- **icon.png**
- components folder out of app folder
- dynamic routes: https://www.udemy.com/course/nextjs-react-the-complete-guide/learn/lecture/41159656#overview
- nested layouts are children of root layout
- `@` is for root folder
- if we are accessing img, we need to add `.src`
- recommendation is to use `<Image>` component (then we do not to use `.src`)
- `'use client;'` put as far down as possible
- `<Suspense>` is component by react to allow us to handle loading state and show fallback content until data are loaded
- `dangerouslySetInnerHTML` xss attack
- `<p className={styles.instructions} dangerouslySetInnerHTML={{
                    __html: '...'
                }}></p>`
- `notFound()` function shows closest `nof-found.js` page
- `use server;` is guaranteed to happen on the server
- `use server;` cannot be in use with `use client;` component
- `npm install slugify xss`
- `import {useFormStatus} from 'react-dom';` must be inside any form component
- `npm run build` prepare project for production
- `npm run build` pre-generate all the pages that can be pre-generated
- `npm start` run production server on localhost
- `revalidatePath('/meals')` tells next.js to revalidate cache of the certain route path
- **/public** folder is only available on development server
- `generateMetadata({params})` used for generating metadata for dynamic pages [Next.js metadata docs](https://nextjs.org/docs/app/api-reference/functions/generate-metadata)

## Section 4: Pages and file-based routing
- somehting like nuxt2
- filenames **matters** (index.js, index.js, ...)
- `import { useRouter } from 'next/router';` -> `const router = useRouter();`
- `router.query` & `router.pathname`
- we can have nested dynamic paths
- **[...slug].js** catch all nested routes -> we got array when use `useRouter()`
- `<Link />` pre-fetch data from page we want to visit as soon as we hover over the component
- `404.js` = custom not found page

## Section 5: Project Time: Working with File-based Routing
- `const { title, image, date, location, id } = props;` => destructurized props
- [Link up to Next.js 13](https://www.udemy.com/course/nextjs-react-the-complete-guide/learn/lecture/34563778#overview)
- `import {Fragment} from "react";` is needed if we have jsx elements and components

## Section 6: Page Pre-Rendering & Data Fetching
- in classic react apps when we fetch some data and we press **CTRL+U**, we cannot see the data
- so the search engines like google etc. won't see the content
- we send request after the page was rendered
- **Page pre-rendering** returns pre-rendered page which is good for SEO
-two forms of pre-rendering: Static generation and Server-side rendering

### Static generation
- idea is pre-generate page during build-time and one we deploy them, they can be cached by CDN
- `export async function getStaticProps(context) { ... }` - name of the function matters
- `export async function getStaticPaths() { ... }` - name of the function matters
- any code we put inside `getStaticProps()` function won't be included in the code bundled when sent back to client (credentials, etc.)
- we need to always return object of props
- we can use imports in `getStaticProps()`
- `process.cwd()` stands for root folder of the project 
- **ISR** - Incremental static generation
- `revalidate: 1 `**really matters in production**
- dynamic pages are not pre-genarated by default
- with `fallback: true` we tell Next.js that the pages are not here

### Server side rendering
- `export async function getServerSideProps() { ... }` - name of the function matters
- `getServerSideProps() { ... }` executes only on server
- `getServerSideProps` & `getStaticProps` needs same parameters
- `{params, req, res}` = default Node.js objects
- 
- ### Client-side data fetching
- some data does not to be pre-rendered (eshop, dashboard, etc)
- `useSwr()` can be used only in react components

## Nápady
- [ ] v administraci při vytváření nového záznamu nejdříve vytvořit skrze modal a potom přesměrovat na celkovou úpravu
- [ ]