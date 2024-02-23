export default function UserProfilePage(props) {
    return (
        <p>
            {props.username}
        </p>
    )
}

export async function getServerSideProps(context) {
    const {params, req, res} = context;

    return {
        props: {
            username: 'Martiiiin'
        }
    }
}