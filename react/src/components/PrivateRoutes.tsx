import { useEffect, useState } from "react";
import { Navigate, Outlet } from "react-router-dom";

const PrivateRoutes = () => {
  const [token] = useState(null);
  //   const auth = { token: token };

  useEffect(() => {
    console.log(token);
  }, []);

  return <> {!token ? <Outlet /> : <Navigate to="/login" replace />}</>;
};

export default PrivateRoutes;
