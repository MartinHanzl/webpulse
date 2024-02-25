import {useEffect, useState} from 'react'
import {Dialog} from '@headlessui/react'
import {Bars3Icon, XMarkIcon} from '@heroicons/react/24/outline'
import Link from 'next/link';
import useSWR from 'swr';
import {Disclosure, Menu, Transition} from '@headlessui/react'
import {Fragment} from "react";
import Image from 'next/image';

function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

export default function Header(props) {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false)
    const [links, setLinks] = useState();

    const locale = props.lang;

    const {data, error} = useSWR(
        'http://localhost:8000/api/links/list/' + props.lang,
        (url) => fetch(url).then(res => res.json())
    );

    useEffect(() => {
        if (data) {
            const links = [];

            for (const key in data) {
                links.push({
                    id: key,
                    ...data[key],
                });
            }

            setLinks(links);
        }
    }, [data]);


    return (
        <header className="fixed inset-x-0 py-4 top-0 z-50 backdrop-blur-sm">
            <div className="lg:container mx-auto">
                <nav className="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                    <div className="flex lg:flex-1">
                        <a href="#" className="-m-1.5 p-1.5">
                            <span className="sr-only">Your Company</span>
                            <img
                                className="h-8 w-auto"
                                src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                                alt=""
                            />
                        </a>
                    </div>
                    <div className="flex lg:hidden">
                        <button
                            type="button"
                            className="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                            onClick={() => setMobileMenuOpen(true)}
                        >
                            <span className="sr-only">Open main menu</span>
                            <Bars3Icon className="h-6 w-6" aria-hidden="true"/>
                        </button>
                    </div>
                    <div className="hidden md:flex lg:flex-1 lg:justify-end">
                        {links && links.map((link) => (
                            <Link key={link.id} href={link.link}
                                  className="text-1xl font-semibold text-slate-100 ml-16">
                                {link.title}
                            </Link>
                        ))}
                        <Menu as="div" className="relative ml-16">
                            <div>
                                <Menu.Button
                                    className="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-slate-800">
                                    <span className="absolute -inset-1.5"/>
                                    {locale === 'cs' &&
                                        <img
                                            className="h-8 w-8 rounded-full"
                                            src="https://flagicons.lipis.dev/flags/4x3/cz.svg"
                                            alt=""
                                        />
                                    }
                                    {locale === 'en' &&
                                        <img
                                            className="h-8 w-8 rounded-full"
                                            src="https://flagicons.lipis.dev/flags/4x3/gb.svg"
                                            alt=""
                                        />
                                    }
                                </Menu.Button>
                            </div>
                            <Transition
                                as={Fragment}
                                enter="transition ease-out duration-100"
                                enterFrom="transform opacity-0 scale-95"
                                enterTo="transform opacity-100 scale-100"
                                leave="transition ease-in duration-75"
                                leaveFrom="transform opacity-100 scale-100"
                                leaveTo="transform opacity-0 scale-95"
                            >
                                <Menu.Items
                                    className="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <Menu.Item>
                                        {({active}) => (
                                            <a
                                                href="/en"
                                                className={classNames(active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700')}
                                            >
                                                Angličtina
                                            </a>
                                        )}
                                    </Menu.Item>
                                    <Menu.Item>
                                        {({active}) => (
                                            <a
                                                href="/cs"
                                                className={classNames(active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700')}
                                            >
                                                Čeština
                                            </a>
                                        )}
                                    </Menu.Item>
                                </Menu.Items>
                            </Transition>
                        </Menu>
                    </div>
                </nav>
                <Dialog as="div" className="md:hidden" open={mobileMenuOpen} onClose={setMobileMenuOpen}>
                    <div className="fixed inset-0 z-50"/>
                    <Dialog.Panel
                        className="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-slate-950/5 px-6 py-10 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10 backdrop-blur-sm">
                        <div className="flex items-center justify-between">
                            <a href="#" className="-m-1.5 p-1.5">
                                <span className="sr-only">Your Company</span>
                                <img
                                    className="h-8 w-auto"
                                    src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                                    alt=""
                                />
                            </a>
                            <button
                                type="button"
                                className="-m-2.5 rounded-md p-2.5 text-gray-700"
                                onClick={() => setMobileMenuOpen(false)}
                            >
                                <span className="sr-only">Close menu</span>
                                <XMarkIcon className="h-6 w-6" aria-hidden="true"/>
                            </button>
                        </div>
                        <div className="mt-6 flow-root">
                            <div className="-my-6 divide-y divide-gray-500/10">
                                <div className="space-y-2 py-6">
                                    {links && links.map((link) => (
                                        <Link
                                            key={link.id}
                                            href={link.link}
                                            className="-mx-3 block rounded-lg px-3 py-2 text-center font-semibold leading-7 text-slate-100 hover:text-slate-400"
                                        >
                                            {link.title}
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </Dialog.Panel>
                </Dialog>
            </div>
        </header>
    );
}
