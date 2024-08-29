import { Suspense, useEffect } from "react";
import { Route, Routes, useLocation } from "react-router-dom";
// import { AuthProvider } from "./context/authContext";
// import App from "./App";
// import { EventProvider } from "./context/EventContext";
import Login from "./pages/Login";
import Register from "./pages/Register";
import Home from "./pages/Home";
import Otp from "./pages/Otp";
import Dashboard from "./pages/Dashboard"
import LandingPage from "./pages/LandingPage";
import { AuthenticationProvider } from "./context/AuthenticationContext";
import RoleBasedRoutes from "./components/RoleBasedRoutes";
import ManageUser from "./pages/ManageUser";
import MainMenu from "./pages/customer/MainMenu";
// import PrivateRoutes from "./pages/PrivateRoutes";

import "preline/preline"
import { IStaticMethods } from "preline/preline";
declare global {
  interface Window {
    HStaticMethods: IStaticMethods;
  }
}

const Root = () => {

  const location = useLocation();

  useEffect(() => {
    window.HStaticMethods.autoInit();
  }, [location.pathname]);

  return (
    <Suspense fallback={<div>Loading...</div>}>
      <AuthenticationProvider>
        <Routes>
          {/* <Route element={<RoleBasedRoutes allowedRoles={["admin"]} />}>
            <Route path="/home" element={<Home />} />
            <Route path="/manage_access" element={<ManageUser />} /> 
          </Route>

          <Route path="/dashboard" element={<Dashboard />} />

          <Route element={<RoleBasedRoutes allowedRoles={["member", "admin"]} />}>
            <Route path="/menu" element={<MainMenu />} />
          </Route> */}


          <Route path="/register" element={<Register />} />
          <Route path="/login" element={<Login />} />
          <Route path="/otp" element={<Otp />} />
        </Routes>
      </AuthenticationProvider>
    </Suspense>
  );
};

export default Root;
