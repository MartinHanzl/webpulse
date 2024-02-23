import fs from "fs/promises";
import path from "path";
import Link from "next/link";

function HomePage(props) {
    const {products} = props;
    return (
        <ul>
            {products.map(product => <li key={product.id}><Link href={`/${product.id}`}>{product.title}</Link></li>)}
        </ul>
    );
}

export default HomePage;

export async function getStaticProps(context) {
    const filePath = path.join(process.cwd(), 'data', 'dummy-backend.json');
    const jsonData = await fs.readFile(filePath);
    const data = JSON.parse(jsonData);

    if (data.products.length === 0) {
        return {notFound: true};
    }

    return {
        props: {
            products: data.products,
        },
        revalidate: 1, // really matters in production
        //redirect: { destination: 'https://google.com/' }
    };
}