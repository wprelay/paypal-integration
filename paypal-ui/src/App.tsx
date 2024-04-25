// @ts-ignore
import React, {FC, useState} from 'react';
import {HashRouter, NavLink, Route, Routes} from "react-router-dom";
import {Settings} from "./pages/Settings";

import './main.css';
import './styles/navbar.css';
import {useLocalState} from "./zustand/localState";
import {toastrError} from "./ToastHelper";
import {axiosClient} from "./components/axios";
import {BatchPayoutItem} from "./pages/BatchPayoutItem";
import {BarLoader} from "react-spinners";

const App: FC = (props) => {
    const [loading, setLoading] = React.useState<boolean>(true);

    // @ts-ignore
    const windowLocation = window.location

    const {setLocalState} = useLocalState();

    const getLocalData = () => {
        axiosClient.get('?action=wp_relay_paypal', {
            params: {
                method: 'get_local_data',
            }
        }).then((response) => {
            setLocalState(response.data.data);
            setLoading(false);
        }).catch(response => {
            toastrError('Error Occurred')
        })
    }

    React.useEffect(() => {
        getLocalData();
    }, [])

    return (
        <div>
            {loading ? <BarLoader
                    color={"#121212"}
                    loading={loading}
                    width="100%"
                    aria-label="Loading Spinner"
                    data-testid="loader"
                /> :
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
                        <Route path="/payouts" element={<BatchPayoutItem/>}></Route>
                    </Routes>
                </HashRouter>
            }
        </div>)
}


export default App;

