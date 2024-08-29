import logo from "/public/dashboarddd/memospace-black.png";
import people from "/public/dashboarddd/people.svg";
import listed from "/public/dashboarddd/listed.svg";
// import useDateDay from "../hooks/useDateDay.tsx";

const Sidebar = () => {
  return (
    <div className="border-[1.5px] border-r-[#D3CCF6] basis-[30%] h-full flex justify-center font-poppins">
      <div className="my-3 flex flex-col items-center gap-y-8 w-3/4">
        <img src={logo} alt="" className="w-3/4" />
        <button className="shadow-sm p-2 w-full">Create Events</button>

        {/* pages component */}
        <div className="flex flex-col gap-2 w-full">
          <p>Pages</p>
          <div className="shadow-md w-full p-2 flex items-center gap-3">
            <img src={people} alt="" className="ml-3" />
            <p className="text-sm">My Profile</p>
          </div>
          <div className="shadow-md w-full p-2 flex items-center gap-3">
            <img src={listed} alt="" className="ml-3" />
            <p className="text-sm">List Events</p>
          </div>
        </div>
        {/* pages component */}

        {/* dragable event component */}
        <div className="flex flex-col gap-2 w-full">
          <p>Dragable Events</p>
          <div className="shadow-md w-full">
            <div className="my-3 mx-4">
              <div className="bg-black rounded-md">luncu</div>
              <div className="flex items-center">
                <input type="checkbox" className="" />
                <p>Remove task after drop</p>
              </div>
            </div>
          </div>
        </div>
        {/* dragable event component */}

        {/* dragable event component */}
        <div className="flex flex-col gap-2 w-full">
          <div className="shadow-md w-full">
            <div className="my-3 mx-4">
              <div className="flex gap-x-3 h-7">
                <div className="bg-black rounded-md">luncu</div>
                <div className="bg-black rounded-md">luncu</div>
                <div className="bg-black rounded-md">luncu</div>
                <div className="bg-black rounded-md">luncu</div>
              </div>

              <div className="w-full border-2">
                <input type="text" className="py-3 outline-none" />
                <button type="submit">Add</button>
              </div>
            </div>
          </div>
        </div>
        {/* dragable event component */}
      </div>
    </div>
  );
};

export default Sidebar;
