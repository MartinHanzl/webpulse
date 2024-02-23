import fs from "fs/promises";
import path from "path";

function HomePage(props) {
    const {products} = props;
    return (
        <ul>
            {products.map(product => <li key={product.id}>{product.title}</li>)}
        </ul>
    );
}

export default HomePage;

export async function getStaticProps(context) {
    console.log('regenerating');
    const filePath = path.join(process.cwd(), 'data', 'dummy-backend.json');
    const jsonData = await fs.readFile(filePath);
    const data = JSON.parse(jsonData);

    return {
        props: {
            products: data.products,
            revalidate: 1 // really matters in production
        },
    };
}