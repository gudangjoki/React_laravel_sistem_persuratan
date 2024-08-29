import { useState } from "react";
import SidebarClinic from "../components/SidebarClinic";
import burger from "../../public/burger.svg";

const Dashboard = () => {
  const [sidebarClick, setSidebarClicked] = useState(false);

  return (
    <div className="h-screen w-full flex">
      <SidebarClinic />
      <div className="flex flex-col flex-1">
        <div className="navbar h-10 w-full bg-[#46C82B]">
          <div className="flex justify-between items-center h-full">
            <button onClick={() => setSidebarClicked(!sidebarClick)}>
              <img src={burger} alt="burger" className="w-10 h-10" />
            </button>
            <div className="flex items-center space-x-4">
              <div className="w-10 h-10 bg-black flex items-center justify-center text-white">LAB</div>
              <div>KLINIK</div>
            </div>
          </div>
        </div>
        <div className="content flex-1">
          {/* Your other content goes here */}
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
