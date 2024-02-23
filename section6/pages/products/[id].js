import {Fragment} from "react";
import path from "path";
import fs from "fs/promises";

export default function ProductDetail(props) {
    const {loadedProduct} = props;

    if (!loadedProduct) {
        return <p>Product was not found.</p>
    }
    return <Fragment>
        <h1>{loadedProduct.title}</h1>
        <p>{loadedProduct.description}</p>
    </Fragment>
}

async function getData() {
    const filePath = path.join(process.cwd(), 'data', 'dummy-backend.json');
    const jsonData = await fs.readFile(filePath);
    const data = JSON.parse(jsonData);

    return data;
}

export async function getStaticProps(context) {
    const {params} = context;
    const productId = params.id;

    const data = await getData();
    const product = data.products.find(product => product.id === productId);

    if (!product) {
        return {notFound: true};
    }

    return {
        props: {
            loadedProduct: product
        }
    }
}

export async function getStaticPaths() {
    const data = await getData();

    const ids = data.products.map(product => product.id);
    const pathsWithParams = ids.map(id => ({params: {id: id}}))
    return {
        paths: pathsWithParams,
        fallback: false // or 'blocking' - when we set blocking, the page wait to be fully generated on the server, and we do not need any fallback in the main component
    }
}