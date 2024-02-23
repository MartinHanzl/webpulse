import {Fragment} from "react";
import path from "path";
import fs from "fs/promises";

export default function ProductDetail() {
    const {loadedProduct} = props;
    return <Fragment>
        <h1>{loadedProduct.title}</h1>
        <p>{loadedProduct.description}</p>
    </Fragment>
}

export async function getStaticProps(context) {
    const {params} = context;
    const productId = params.id;

    const filePath = path.join(process.cwd(), 'data', 'dummy-backend.json');
    const jsonData = await fs.readFile(filePath);
    const data = JSON.parse(jsonData);

    const product = data.products.find(product => product.id === productId);

    if (!product) {
        return {notFound: true};
    }

    console.log(params);
    return {
        props: {
            loadedProduct: product
        }
    }
}