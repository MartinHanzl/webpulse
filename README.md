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


- Next.js app are rendered on server side
- in file based routing every new route should b*e* named `page.js`
- name of component does not matter
- app router vs pages router (section 1, part 7)
- `page.js`, `layout.js`, `not-found.js`, `error.js` are reserved names (https://www.udemy.com/course/nextjs-react-the-complete-guide/learn/lecture/41161954#overview)
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
- 


## Nápady
- [ ] v administraci při vytváření nového záznamu nejdříve vytvořit skrze modal a potom přesměrovat na celkovou úpravu
- [ ]