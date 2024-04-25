import React, {FC, useEffect} from 'react';
import '../../styles/appHeader.css'
import {useLocalState} from "../../zustand/localState";

const AppHeader: FC = () => {

    const {localState} = useLocalState();

    return (

        <div
            className="relay-wp-app-header wrp-full relay-wp-h-14 wrp-border-white wrp-flex wrp-justify-between wrp-items-center relay-wp-px-3 ">
            <div className='wrp-ml-3'>
                <span className='wrp-flex wrp-justify-between wrp-items-center wrp-gap-2'>
                    <svg width="60" height="60" viewBox="0 0 82 82" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fillRule="evenodd" clipRule="evenodd"
                      d="M31.7843 11.7362C38.7193 9.87799 45.8477 13.9936 47.7059 20.9286L48.35 23.3322L42.5544 24.8851L41.9104 22.4815C40.9098 18.7473 37.0714 16.5312 33.3372 17.5318L17.962 21.6516C14.2277 22.6521 12.0117 26.4905 13.0123 30.2248L17.132 45.5999C18.1326 49.3342 21.971 51.5503 25.7052 50.5497L41.0804 46.4299C44.8147 45.4293 47.0308 41.591 46.0302 37.8567L45.6603 36.4762L51.4558 34.9233L51.8257 36.3038C53.684 43.2389 49.5684 50.3672 42.6333 52.2255L27.2581 56.3452C20.3231 58.2035 13.1947 54.0879 11.3365 47.1529L7.2167 31.7777C5.35846 24.8426 9.47403 17.7142 16.4091 15.856L31.7843 11.7362ZM50.2246 69.2638C43.2895 71.122 36.1612 67.0064 34.3029 60.0714L33.6589 57.6678L39.4544 56.1149L40.0985 58.5185C41.0991 62.2527 44.9374 64.4688 48.6717 63.4682L64.0469 59.3485C67.7811 58.3479 69.9972 54.5095 68.9966 50.7752L64.8769 35.4001C63.8763 31.6658 60.0379 29.4497 56.3036 30.4503L40.9285 34.5701C37.1942 35.5707 34.9781 39.409 35.9787 43.1433L36.3486 44.5238L30.5531 46.0767L30.1832 44.6962C28.3249 37.7611 32.4405 30.6328 39.3756 28.7745L54.7507 24.6548C61.6858 22.7965 68.8142 26.9121 70.6724 33.8471L74.7922 49.2223C76.6504 56.1574 72.5348 63.2858 65.5998 65.144L50.2246 69.2638Z"
                      fill="#1D1D20"/>
                </svg>
                    <span className='wrp-text-3xl wrp-text-black wrp-font-bold'>{localState.plugin_name}</span>
                </span>
            </div>
            <div className="relay-wp-app-header-right wrp-flex wrp-justify-between !wrp-w-fit ">
                <div className="relay-wp-app-header-right-child relay-wp-app-header-right-child-version">
                    <span>V {localState.version}</span>
                </div>
            </div>
        </div>
    )
};

export default AppHeader;

