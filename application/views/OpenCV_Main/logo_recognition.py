# Authored by Tan Tack Poh
# Logo recognition algorithm (SIFT Feature Detection) for Bill.eGoat

# import libraries
import os
import numpy as np
import cv2
from matplotlib import pyplot as plt

"""
    This function initiates the SIFT detector, which relies on keypoints, descriptors
    and matcher to perform the algorithm. Each keypoint and descriptor pair up to find a point,
    and the matcher takes the matched pair to perform matching. When all the matchings are done,
    they are sorted from shortest distance (shorter being better matched.

    Input:
    userImg - Grayscale user logo
    DBImg - Grayscale database logo

    Output:
    matchedPairs - An array of all the matching descriptions, consist of distances.
    kp1, kp2 - Matched pair's element, kp1 and kp2 takes each side of the pair and becomes a set of it.
"""
def performMatching(userImg, DBImg):

    # Initiate SIFT detector
    orb = cv2.ORB()

    # find the keypoints and descriptors with SIFT
    kp1, des1 = orb.detectAndCompute(userImg, None)
    kp2, des2 = orb.detectAndCompute(DBImg, None)

    # create BFMatcher object
    bf = cv2.BFMatcher(cv2.NORM_HAMMING, crossCheck=True)

    # Match descriptors (performs actual matching).
    matchedPairs = bf.match(des1, des2)

    # Sort them in the order of their distance.
    # Shorter distance means better match. A perfectly identical image will
    # yield all matched point distance to be 0.0
    matchedPairs = sorted(matchedPairs, key = lambda x:x.distance)

    return matchedPairs, kp1, kp2

"""
    My own implementation of cv2.drawMatches as OpenCV 2.4.9
    does not have this function available but it's supported in
    OpenCV 3.0.0

    This function takes in two images with their associated 
    keypoints, as well as a list of DMatch data structure (matches) 
    that contains which keypoints matched in which images.

    An image will be produced where a montage is shown with
    the first image followed by the second image beside it.

    Keypoints are delineated with circles, while lines are connected
    between matching keypoints.

    Input:
    img1,img2 - Grayscale images
    kp1,kp2 - Detected list of keypoints through any of the OpenCV keypoint 
              detection algorithms
    matches - A list of matches of corresponding keypoints through any
              OpenCV keypoint matching algorithm

    Output:
    out - An image of two compared images, placed side-by-side with top 10
          matches marked with a pair of points connected by a green line
"""
def drawMatches(img1, kp1, img2, kp2, matches):
    
    # Create a new output image that concatenates the two images together
    # (a.k.a) a montage
    rows1 = img1.shape[0]
    cols1 = img1.shape[1]
    rows2 = img2.shape[0]
    cols2 = img2.shape[1]

    out = np.zeros((max([rows1,rows2]),cols1 + cols2, 3), dtype='uint8')

    # Place the first image to the left
    out[:rows1,:cols1] = np.dstack([img1, img1, img1])

    # Place the next image to the right of it
    out[:rows2,cols1:] = np.dstack([img2, img2, img2])

    # For each pair of points we have between both images
    # draw circles, then connect a line between them
    for mat in matches:

        # Get the matching keypoints for each of the images
        img1_idx = mat.queryIdx
        img2_idx = mat.trainIdx

        # x - columns
        # y - rows
        (x1,y1) = kp1[img1_idx].pt
        (x2,y2) = kp2[img2_idx].pt

        # Draw a small circle at both co-ordinates
        # radius 4
        # colour blue
        # thickness = 1
        cv2.circle(out, (int(x1),int(y1)), 4, (255, 0, 0), 1)   
        cv2.circle(out, (int(x2)+cols1,int(y2)), 4, (255, 0, 0), 1)

        # Draw a line in between the two points
        # thickness = 1
        # colour blue
        cv2.line(out, (int(x1),int(y1)), (int(x2)+cols1,int(y2)), (255, 0, 0), 1)


    # Also return the image if you'd like a copy
    return out

# Read in user image input, and an image from database for detection (read in a grayscale)
# Directory must be in full for py file (i.e start from opt bitnami folder)
userImg = cv2.imread('/opt/bitnami/apache2/htdocs/images/detection_result/queryImage.jpg', 0)
DBImg = cv2.imread('/opt/bitnami/apache2/htdocs/images/detection_result/trainingImage.jpg', 0)

# Perform matching
matches, kp1, kp2 = performMatching(userImg, DBImg);

# initializing average
distArray = np.empty( shape=(0, 0), dtype = np.float32 )

# Print out distances, for debug only on console (not shown on browser)
for x in matches:
    distArray = np.append(distArray,np.float32(x.distance))   

# Draw first 10 matches.
AnalyzedImg = drawMatches(userImg, kp1, DBImg, kp2, matches[:10])

# Output matched image results
cv2.imwrite('/opt/bitnami/apache2/htdocs/images/detection_result/recognitionResult.jpg', AnalyzedImg)

# return average
print np.average(distArray)
