import { useLocation, Navigate, Outlet } from "react-router-dom";
import { jwtDecode } from "jwt-decode";
import { ReactNode, useEffect, useState } from "react";

interface Props {
  allowedRoles: string[];
  children?: ReactNode;
}

interface Data {
  role: string;
  email: string;
}

const RoleBasedRoutes = ({ allowedRoles }: Props) => {
  const [guarded, setGuarded] = useState<boolean>(true);
  const location = useLocation();
  const [decoded, setDecoded] = useState<Data | null>(null);
  const [loading, setLoading] = useState<boolean>(true);
  const token = localStorage.getItem("token");

  useEffect(() => {
    const fetchToken = async () => {
      if (token) {
        try {
          const decodedToken = jwtDecode<Data>(token);
          setDecoded(decodedToken);
          console.log(decodedToken)
          // console.log("Decoded token role:", decodedToken.role);
          // console.log(allowedRoles)
          setLoading(false);
        } catch (error) {
          console.error("Error decoding token:", error);
        }
      } else {
        console.log("No token in local storage");
        setDecoded({ email: "", role: "" });
      }
    };

    fetchToken();
  }, []);

  useEffect(() => {
    console.log(decoded?.role);
    const roles: string | undefined = decoded?.role
    if (!roles) return;
    const isAllowed = allowedRoles?.includes(roles[0])
    console.log(isAllowed);
    setGuarded(isAllowed);
  }, [decoded])

  while (loading) return <div>Loading....</div>; // ganti dengan komponen pada suspense

  // allowedRoles[0] == decoded?.role
  return decoded && guarded ? (
    // return decoded ? (
    <Outlet context={{ "role": decoded?.role }} />
  ) : allowedRoles[0] != "" ? (
    <Navigate to="/login" state={{ from: location }} replace />
  ) : (
    <Navigate to={location.state?.from} state={{ from: location }} replace />
  );
};

export default RoleBasedRoutes;
