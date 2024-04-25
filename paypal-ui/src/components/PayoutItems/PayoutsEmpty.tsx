import React from "react";

export const PayoutsEmpty = () => {
    return (
        <div className="wrp-flex wrp-items-center wrp-flex-col wrp-justify-center wrp-text-center wrp-h-full">
            <div className="wrp-mx-auto wrp-my-auto wrp-flex wrp-flex-col wrp-gap-5 wrp-p-5">
                <div><i className="wpr wpr-list-empty wrp-text-6xl "></i></div>
                <div><span className="wrp-text-lg wrp-font-bold">No Sales Yet</span></div>
                <div>
                    <p className="wrp-text-sm ">Oops, it appears that no sales made.</p>
                </div>

            </div>
        </div>)
}