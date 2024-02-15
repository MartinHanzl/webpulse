export default function BlogPostPage({params}) {
    return (
        <main>
            <h1>{params.slug}</h1>
            <h2>{params.id}</h2>
        </main>
    )
}