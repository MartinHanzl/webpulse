import {Disclosure, Menu, Transition} from '@headlessui/react'
import {Fragment} from "react";
import Image from 'next/image';

function classNames(...classes) {
    return classes.filter(Boolean).join(' ')
}

export default function HeaderLanguageSwitcher(props) {
    const locale = props.lang;
    const languages = props.languages;

    return (
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
                    className="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-slate-950 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    {languages.map((language) => {
                        <div key={language.id}>
                            <Menu.Item>
                                {({active}) => (
                                    <a
                                        href={`/${language.locale}`}
                                        className={classNames(active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700')}
                                    >
                                        {language.name}
                                    </a>
                                )}
                            </Menu.Item>
                        </div>
                    })}
                </Menu.Items>
            </Transition>
        </Menu>
    )
}