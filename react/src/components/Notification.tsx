import warn from "/public/dashboarddd/warn.svg";

const Notification = () => {
  return (
    <div className="shadow-lg w-[27.5%] font-poppins h-full right-0 absolute z-[10] bg-white">
      <div className="flex justify-center flex-col items-center mt-6 mx-7">
        <h1 className="text-2xl font-semibold">Notification</h1>

        <div className="mt-7 w-full shadow-md flex text-[12px] items-center">
          <img src={warn} alt="warn" className="mx-3 w-8" />
          <div className="flex flex-col gap-2 my-3">
            <div className="flex justify-between">
              <h3 className="text-[#444444]">Warning</h3>
              <p className="text-[#A097CF]">21 April</p>
            </div>
            <p>Lorem ipsum asmset aseeeeetttt</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Notification;
