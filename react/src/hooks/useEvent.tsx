import { useContext } from "react";
import { EventContext } from "../context/EventContext";

export const useEvent = () => {
    return useContext(EventContext);
};