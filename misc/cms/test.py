from urllib.robotparser import RobotFileParser

robots = RobotFileParser()
robots.set_url("https://gta.wiki/robots.txt")
robots.read()

url = "https://gta.wiki/wiki/Prologue"

print("Allow all:", robots.allow_all)
print("Disallow all:", robots.disallow_all)
print("Can fetch:", robots.can_fetch("GuideGenerator/0.1", url))