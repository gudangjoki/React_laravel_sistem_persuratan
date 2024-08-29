import { useContext } from "react";
import { AuthenticationContext } from "../context/AuthenticationContext";

export const useAuth = () => {
    return useContext(AuthenticationContext);
};