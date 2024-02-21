'use server';

export async function shareMeal(FormData) {
    const meal = {
        title: FormData.get('title'),
        summary: FormData.get('summary'),
        creator_email: FormData.get('email'),
        creator: FormData.get('name'),
        instructions: FormData.get('instructions'),
        image: FormData.get('image')
    }
}