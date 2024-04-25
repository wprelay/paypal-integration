import {create} from "zustand";
import {LocalState} from "./zustand.types";


export const useLocalState = create<LocalState>((set) => ({
    localState: {},
    setLocalState: (data) => set({localState: data})
}));
