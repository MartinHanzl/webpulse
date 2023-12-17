'use client'
import { useState } from 'react';
import axios, {request} from 'axios';
import { useRouter } from 'next/navigation';
import toast from "react-hot-toast";
import {signIn} from "next-auth/react";

export default function Page() {
    const [credentials, setCredentials] = useState({ email: '', password: '' });
    const router = useRouter();

    const handleChange = (e) => {
        setCredentials({ ...credentials, [e.target.name]: e.target.value });
    };

    const handleCLick = async (e) => {
        try {
            const res = await axios.post('http://localhost:8000/api/auth/login', credentials);
            document.cookie = `token=${res.data.token}; path=/; HttpOnly`;
            toast.success('Login was successful');
            await router.push('/dashboard');
        } catch (error) {
            console.error(error);
            toast.success('Login was not successful');
        }
    };
    return (
        <main className="flex min-h-screen flex-col items-center p-24">
            <div className="space-y-10">
                <div className="border-b border-gray-900/10 pb-12">
                    <h2 className="text-base font-semibold leading-7 text-sky-950 text-center">Login into Web-pulse
                        system</h2>

                    <div className="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div className="sm:col-span-3">
                            <label htmlFor="email" className="block text-sm font-medium leading-6 text-gray-900">
                                Email
                            </label>
                            <div className="mt-2">
                                <input
                                    onChange={handleChange}
                                    type="email"
                                    name="email"
                                    id="email"
                                    className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                            </div>
                        </div>

                        <div className="sm:col-span-3">
                            <label htmlFor="password" className="block text-sm font-medium leading-6 text-gray-900">
                                Password
                            </label>
                            <div className="mt-2">
                                <input
                                    onChange={handleChange}
                                    type="password"
                                    name="password"
                                    id="password"
                                    autoComplete="family-name"
                                    className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div className="mt-2 flex items-center justify-end gap-x-6">
                <button
                    onClick={signIn}
                    className="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Login
                </button>
            </div>
        </main>
    )
}
