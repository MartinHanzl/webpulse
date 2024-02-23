export default function UserProfilePage(props) {
    return (
        <p>
            {props.username}
        </p>
    )
}

export async function getServerSideProps(context) {
    return {
        props: {
            username: 'Martiiiin'
        }
    }
}