import { CalendarProvider } from '../context/CalendarContext'
import Dashboard from './Dashboard'

const Base = () => {
  return (
    <>
    <CalendarProvider>
        <Dashboard />
    </CalendarProvider>
    </>
  )
}

export default Base