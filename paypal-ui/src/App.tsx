// @ts-ignore
import React, {FC, useState} from 'react';
import {HashRouter, NavLink, Route, Routes} from "react-router-dom";
import {Settings} from "./pages/Settings";

import './main.css';
import './styles/navbar.css';

const App: FC = (props) => {
    const [loading, setLoading] = React.useState<boolean>(true);

    // @ts-ignore
    const windowLocation = window.location


    return (
        <HashRouter>
        <div>
            <nav
                className="relay-wp-nav-bar wrp-flex xl:wrp-justify-start lg:wrp-justify-start lg:wrp-gap-5 md:wrp-gap-5 ">
                <NavLink
                    className=" wrp-items-stretch wrp-flex wrp-rounded-lg lg:!wrp-h-11.5 relay-wp-nav-link  xl:wrp-px-4 xl:wrp-py-3 lg:wrp-px-3 lg:wrp-py-3 md:wrp-px-1 md:wrp-py-2 md:wrp-h-10 wrp-px-1 wrp-py-2 wrp-h-10 "
                    to="/">
                    <i className='rwp rwp-dashboard  lg:wrp-text-xl  md:wrp-text-4.5 wrp-text-4.5'></i>
                    <span
                        className='wrp-ml-2 xl:wrp-text-4 lg:wrp-text-3.5 wrp-text-xs wrp-flex wrp-items-center'>Settings</span>
                </NavLink>
                <NavLink
                    className=" wrp-items-stretch wrp-flex wrp-rounded-lg lg:!wrp-h-11.5 relay-wp-nav-link  xl:wrp-px-4 xl:wrp-py-3 lg:wrp-px-3 lg:wrp-py-3 md:wrp-px-1 md:wrp-py-2 md:wrp-h-10 wrp-px-1 wrp-py-2 wrp-h-10 "
                    to="/payouts">
                    <i className='rwp rwp-dashboard  lg:wrp-text-xl  md:wrp-text-4.5 wrp-text-4.5'></i>
                    <span
                        className='wrp-ml-2 xl:wrp-text-4 lg:wrp-text-3.5 wrp-text-xs wrp-flex wrp-items-center'>Payout Items</span>
                </NavLink>
            </nav>
        </div>
            <Routes>
                <Route path="/" element={<Settings/>}></Route>
                <Route path="/payouts" element={<Settings/>}></Route>
            </Routes>
        </HashRouter>
    )
};

export default App;

