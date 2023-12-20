'use client'
// In pages/admin.js or similar
export const getServerSideProps = async (context) => {
    const { req } = context;
    const token = req.cookies.token; // Assuming the token is stored in a cookie

    if (!token) {
        return {
            redirect: {
                destination: '/login',
                permanent: false,
            },
        };
    }

    return { props: {} };
};

export default function Page() {
    return (
        <main className="flex min-h-screen flex-col items-center p-24">
            <h1>Hurá píčo</h1>
        </main>
    )
}
